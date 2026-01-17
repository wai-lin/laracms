<?php
use App\Models\Page;
use App\Models\PageTemplate;
use App\Models\PageFieldValue;
use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

new #[Title('Create Page')] class extends Component {
    use WithFileUploads;

    public ?int $page_template_id = null;
    public string $title = '';
    public string $slug = '';
    public string $meta_description = '';
    public string $status = 'draft';
    public ?string $published_at = null;
    public array $fieldValues = [];
    public array $fieldUploads = [];

    public function updatedTitle(): void
    {
        $this->slug = Str::slug($this->title);
    }

    public function updatedStatus(): void
    {
        if ($this->status === 'published' && !$this->published_at) {
            $this->published_at = now()->format('Y-m-d\TH:i');
        }
        if ($this->status === 'draft') {
            $this->published_at = null;
        }
    }

    public function save(): void
    {
        $this->validate([
            'page_template_id' => 'required|exists:page_templates,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
        ]);
        $page = Page::create([
            'page_template_id' => $this->page_template_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'meta_description' => $this->meta_description,
            'status' => $this->status,
            'published_at' => $this->status !== 'draft' ? $this->published_at : null,
        ]);
        $template = PageTemplate::with('fields')->find($this->page_template_id);
        
        foreach ($template->fields as $field) {
            $value = null;
            if ($field->type === 'image' && isset($this->fieldUploads[$field->id])) {
                $value = $this->fieldUploads[$field->id]->store('pages', 'public');
            } elseif ($field->type === 'boolean') {
                $value = !empty($this->fieldValues[$field->id]) ? '1' : '0';
            } else {
                $value = $this->fieldValues[$field->id] ?? null;
            }
            PageFieldValue::create([
                'page_id' => $page->id,
                'page_template_field_id' => $field->id,
                'value' => $value,
            ]);
        }
        $this->redirect(route('admin.pages.edit', $page), navigate: true);
    }
    
    public function with(): array
    {
        return [
            'templates' => PageTemplate::all(),
            'templateFields' => $this->page_template_id
                ? PageTemplate::find($this->page_template_id)->fields
                : collect(),
        ];
    }
};
?>

<div class="max-w-2xl">
    <flux:heading size="xl" class="mb-6">{{ __('Create Page') }}</flux:heading>
    <form wire:submit="save" class="space-y-6">
        <flux:select wire:model.live="page_template_id" :label="__('Template')" required>
            <flux:select.option value="">{{ __('Select a template...') }}</flux:select.option>
            @foreach ($templates as $template)
            <flux:select.option :value="$template->id">{{ $template->name }}</flux:select.option>
            @endforeach
        </flux:select>

        @if ($page_template_id)
            <flux:input wire:model.live.debounce.300ms="title" :label="__('Title')" required />
            <flux:input wire:model="slug" :label="__('Slug')" required />
            <flux:textarea wire:model="meta_description" :label="__('Meta Description')" rows="2" />
            <flux:select wire:model.live="status" :label="__('Status')">
                <flux:select.option value="draft">Draft</flux:select.option>
                <flux:select.option value="published">Published</flux:select.option>
                <flux:select.option value="scheduled">Scheduled</flux:select.option>
            </flux:select>

            @if ($status !== 'draft')
                <flux:input wire:model="published_at" type="datetime-local" :label="__('Publish Date')" />
            @endif

            @if ($templateFields->isNotEmpty())
                <flux:separator />
                <flux:heading size="lg">{{ __('Content Fields') }}</flux:heading>

                @foreach ($templateFields as $field)
                    <div wire:key="field-{{ $field->id }}">
                        @switch($field->type)
                            @case('text')
                            <flux:input wire:model="fieldValues.{{ $field->id }}" :label="$field->label" :required="$field->required" />
                            @break
                            @case('textarea')
                            <flux:textarea wire:model="fieldValues.{{ $field->id }}" :label="$field->label" :required="$field->required" rows="4" />
                            @break
                            @case('richtext')
                            <x-richtext-editor wire:model="fieldValues.{{ $field->id }}" name="field-{{ $field->id }}" :label="$field->label" :required="$field->required" />
                            @break
                            @case('image')
                            <div>
                                <flux:input wire:model="fieldUploads.{{ $field->id }}" type="file" accept="image/*" :label="$field->label" />
                                @if (isset($fieldUploads[$field->id]))
                                    <img src="{{ $fieldUploads[$field->id]->temporaryUrl() }}" class="mt-2 h-32 rounded" />
                                @endif
                            </div>
                            @break
                            @case('boolean')
                            <flux:checkbox wire:model="fieldValues.{{ $field->id }}" :label="$field->label" />
                            @break
                        @endswitch
                    </div>
                @endforeach
            @endif

            <div class="flex gap-2">
                <flux:button type="submit" variant="primary">{{ __('Create Page') }}</flux:button>
                <flux:button :href="route('admin.pages.index')" variant="ghost" wire:navigate>{{ __('Cancel') }}</flux:button>
            </div>
        @endif
    </form>
</div>
