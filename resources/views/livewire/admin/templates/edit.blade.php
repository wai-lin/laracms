<?php

use App\Models\PageTemplate;
use App\Models\PageTemplateField;
use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

new #[Title('Edit Template')] class extends Component {
    public PageTemplate $pageTemplate;

    public string $name = '';
    public string $slug = '';
    public string $description = '';

    public bool $showFieldModal = false;
    public ?int $editingFieldId = null;
    public string $fieldLabel = '';
    public string $fieldName = '';
    public string $fieldType = 'text';
    public bool $fieldRequired = false;

    public function mount(PageTemplate $pageTemplate): void
    {
        $this->pageTemplate = $pageTemplate;
        $this->name = $pageTemplate->name;
        $this->slug = $pageTemplate->slug;
        $this->description = $pageTemplate->description ?? '';
    }

    public function resetFieldForm(): void
    {
        $this->editingFieldId = null;
        $this->fieldLabel = '';
        $this->fieldName = '';
        $this->fieldType = 'text';
        $this->fieldRequired = false;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:page_templates,name,' . $this->pageTemplate->id,
            'slug' => 'required|string|max:255|unique:page_templates,slug,' . $this->pageTemplate->id,
            'description' => 'nullable|string|max:1000',
        ]);
        $this->pageTemplate->update($validated);
        $this->dispatch('template-saved');
    }

    public function openFieldModal(?int $fieldId = null): void
    {
        if ($fieldId) {
            $field = PageTemplateField::find($fieldId);
            $this->editingFieldId = $fieldId;
            $this->fieldLabel = $field->label;
            $this->fieldName = $field->name;
            $this->fieldType = $field->type;
            $this->fieldRequired = $field->required;
        } else {
            $this->resetFieldForm();
        }
        $this->showFieldModal = true;
    }

    public function closeFieldModal(): void
    {
        $this->showFieldModal = false;
        $this->resetFieldForm();
    }

    public function updatedFieldLabel(): void
    {
        if (!$this->editingFieldId) {
            $this->fieldName = Str::snake($this->fieldLabel);
        }
    }

    public function saveField(): void
    {
        $validated = $this->validate([
            'fieldLabel' => 'required|string|max:255',
            'fieldName' => 'required|string|max:255',
            'fieldType' => 'required|in:text,textarea,richtext,image,boolean',
            'fieldRequired' => 'boolean',
        ]);
        if ($this->editingFieldId) {
            PageTemplateField::find($this->editingFieldId)->update([
                'label' => $this->fieldLabel,
                'name' => $this->fieldName,
                'type' => $this->fieldType,
                'required' => $this->fieldRequired,
            ]);
        } else {
            $maxOrder = $this->pageTemplate->fields()->max('order') ?? 0;
            $this->pageTemplate->fields()->create([
                'label' => $this->fieldLabel,
                'name' => $this->fieldName,
                'type' => $this->fieldType,
                'required' => $this->fieldRequired,
                'order' => $maxOrder + 1,
            ]);
        }
        $this->closeFieldModal();
        $this->pageTemplate->refresh();
    }

    public function deleteField(int $fieldId): void
    {
        PageTemplateField::destroy($fieldId);
        $this->pageTemplate->refresh();
    }

    public function moveFieldUp(int $fieldId): void
    {
        $field = PageTemplateField::find($fieldId);
        $previous = $this->pageTemplate->fields()->where('order', '<', $field->order)->orderByDesc('order')->first();
        if ($previous) {
            $temp = $field->order;
            $field->update(['order' => $previous->order]);
            $previous->update(['order' => $temp]);
            $this->pageTemplate->refresh();
        }
    }

    public function moveFieldDown(int $fieldId): void
    {
        $field = PageTemplateField::find($fieldId);
        $next = $this->pageTemplate->fields()->where('order', '>', $field->order)->orderBy('order')->first();
        if ($next) {
            $temp = $field->order;
            $field->update(['order' => $next->order]);
            $next->update(['order' => $temp]);
            $this->pageTemplate->refresh();
        }
    }

    public function with(): array
    {
        return [
            'fields' => $this->pageTemplate->fields()->orderBy('order')->get(),
        ];
    }
};
?>

<div class="max-w-4xl">
    <flux:heading size="xl" class="mb-6">{{ __('Edit Template') }}</flux:heading>
    <form wire:submit="save" class="space-y-6 mb-10">
        <flux:input wire:model="name" :label="__('Name')" required />
        <flux:input wire:model="slug" :label="__('Slug')" required />
        <flux:textarea wire:model="description" :label="__('Description')" rows="2" />
        <div class="flex gap-2 items-center">
            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
            <flux:button :href="route('admin.templates.index')" variant="ghost" wire:navigate>{{ __('Back') }}
            </flux:button>
            <x-action-message on="template-saved">{{ __('Saved.') }}</x-action-message>
        </div>
    </form>

    <flux:separator class="my-8" />

    <div class="flex items-center justify-between mb-4">
        <flux:heading size="lg">{{ __('Fields') }}</flux:heading>
        <flux:button size="sm" wire:click="openFieldModal">{{ __('Add Field') }}</flux:button>
    </div>

    @if ($fields->isEmpty())
        <flux:text class="text-zinc-500">{{ __('No fields yet.') }}</flux:text>
    @else
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Order') }}</th>
                    <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Label') }}</th>
                    <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Name') }}</th>
                    <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Type') }}</th>
                    <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Required') }}</th>
                    <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @foreach ($fields as $index => $field)
                    <tr wire:key="{{ $field->id }}">
                        <td class="py-3">
                            <div class="flex gap-1">
                                <flux:button
                                    size="xs"
                                    variant="filled"
                                    wire:click="moveFieldUp({{ $field->id }})"
                                    :disabled="$index === 0"
                                    class="!text-blue-700 disabled:!text-neutral-500"
                                >
                                    &uarr;
                                </flux:button>
                                <flux:button
                                    size="xs"
                                    variant="filled"
                                    wire:click="moveFieldDown({{ $field->id }})"
                                    :disabled="$index === $fields->count() - 1"
                                    class="!text-blue-700 disabled:!text-neutral-500"
                                >
                                    &darr;
                                </flux:button>
                            </div>
                        </td>
                        <td class="py-3">{{ $field->label }}</td>
                        <td class="py-3 text-zinc-500 font-mono text-sm">{{ $field->name }}</td>
                        <td class="py-3">{{ $field->type }}</td>
                        <td class="py-3">{{ $field->required ? 'Yes' : 'No' }}</td>
                        <td class="py-3">
                            <div class="flex gap-2">
                                <flux:button size="sm" wire:click="openFieldModal({{ $field->id }})">
                                    {{ __('Edit') }}</flux:button>
                                <flux:modal.trigger :name="'delete-field-'.$field->id">
                                    <flux:button size="sm" variant="danger">{{ __('Delete') }}</flux:button>
                                </flux:modal.trigger>
                            </div>
                            <flux:modal :name="'delete-field-'.$field->id" class="max-w-sm">
                                <div class="space-y-4">
                                    <flux:heading size="lg">{{ __('Delete Field?') }}</flux:heading>
                                    <flux:text>{{ __('This cannot be undone.') }}</flux:text>
                                    <div class="flex gap-2 justify-end">
                                        <flux:modal.close>
                                            <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                                        </flux:modal.close>
                                        <flux:button variant="danger" wire:click="deleteField({{ $field->id }})">
                                            {{ __('Delete') }}</flux:button>
                                    </div>
                                </div>
                            </flux:modal>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <flux:modal wire:model="showFieldModal" class="max-w-md">
        <div class="space-y-4">
            <flux:heading size="lg">{{ $editingFieldId ? __('Edit Field') : __('Add Field') }}</flux:heading>
            <flux:input wire:model.live="fieldLabel" :label="__('Label')" required />
            <flux:input wire:model="fieldName" :label="__('Name')" required />
            <flux:select wire:model="fieldType" :label="__('Type')">
                <flux:select.option value="text">Text</flux:select.option>
                <flux:select.option value="textarea">Text Area</flux:select.option>
                <flux:select.option value="richtext">Rich Text</flux:select.option>
                <flux:select.option value="image">Image</flux:select.option>
                <flux:select.option value="boolean">Boolean</flux:select.option>
            </flux:select>
            <flux:checkbox wire:model="fieldRequired" :label="__('Required')" />
            <div class="flex gap-2 justify-end pt-4">
                <flux:button variant="ghost" wire:click="closeFieldModal">{{ __('Cancel') }}</flux:button>
                <flux:button variant="primary" wire:click="saveField">{{ $editingFieldId ? __('Save') : __('Add') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
