<?php
use App\Models\Page;
use App\Models\PageTemplate;
use App\Models\PageFieldValue;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

new #[Title('Edit Page')] class extends Component {
    use WithFileUploads;

    public Page $page;
    public string $title = '';
    public string $slug = '';
    public string $meta_description = '';
    public string $status = 'draft';
    public ?string $published_at = null;
    public array $fieldValues = [];
    public array $fieldUploads = [];

    public function mount(Page $page): void
    {
        $this->page = $page;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->meta_description = $page->meta_description ?? '';
        $this->status = $page->status;
        $this->published_at = $page->published_at?->format('Y-m-d\TH:i');
        foreach ($page->fieldValues as $fv) {
            $this->fieldValues[$fv->page_template_field_id] = $fv->value;
        }
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
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $this->page->id,
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
        ]);
        $this->page->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'meta_description' => $this->meta_description,
            'status' => $this->status,
            'published_at' => $this->status !== 'draft' ? $this->published_at : null,
        ]);
        foreach ($this->page->template->fields as $field) {
            $value = $this->fieldValues[$field->id] ?? null;
            if ($field->type === 'image' && isset($this->fieldUploads[$field->id])) {
                // Delete old image if exists
                $oldValue = $this->page->fieldValues->where('page_template_field_id', $field->id)->first();
                if ($oldValue?->value) {
                    Storage::disk('s3')->disk('public')->delete($oldValue->value);
                }
                $value = $this->fieldUploads[$field->id]->store('pages', 'public');
            } elseif ($field->type === 'boolean') {
                $value = !empty($this->fieldValues[$field->id]) ? '1' : '0';
            }
            PageFieldValue::updateOrCreate(
                [
                    'page_id' => $this->page->id,
                    'page_template_field_id' => $field->id,
                ],
                ['value' => $value]
            );
        }
        $this->dispatch('page-saved');
    }

    public function with(): array
    {
        return [
            'templateFields' => $this->page->template?->fields ?? collect(),
        ];
    }
};
?>

<div class="max-w-2xl">
    <flux:heading size="xl" class="mb-6">{{ __('Edit Page') }}</flux:heading>

    <form wire:submit="save" class="space-y-6">
        <flux:input wire:model="title" :label="__('Title')" required />
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
                            @elseif (!empty($fieldValues[$field->id]))
                                <img src="{{ Storage::disk('s3')->url($fieldValues[$field->id]) }}" class="mt-2 h-32 rounded" />
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

        <div class="flex gap-2 items-center">
            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
            <flux:button :href="route('admin.pages.index')" variant="ghost" wire:navigate>{{ __('Back') }}</flux:button>
            <x-action-message on="page-saved">{{ __('Saved.') }}</x-action-message>
        </div>
    </form>
</div>
