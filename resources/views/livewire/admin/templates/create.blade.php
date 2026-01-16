<?php

use App\Models\PageTemplate;
use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

new #[Title('Create Template')] class extends Component {
    public string $name = '';
    public string $slug = '';
    public string $description = '';

    public function updatedName(): void
    {
        $this->slug = Str::slug($this->name);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:page_templates,name',
            'slug' => 'required|string|max:255|unique:page_templates,slug',
            'description' => 'nullable|string|max:1000',
        ]);
        $template = PageTemplate::create($validated);
        $this->redirect(route('admin.templates.edit', $template), navigate: true);
    }
};
?>

<div class="max-w-2xl">
    <flux:heading size="xl" class="mb-6">{{ __('Create Template') }}</flux:heading>
    <form wire:submit="save" class="space-y-6">
        <flux:input wire:model.live="name" :label="__('Name')" placeholder="e.g. Landing Page" required />
        <flux:input wire:model="slug" :label="__('Slug')" placeholder="e.g. landing-page" required />
        <flux:textarea wire:model="description" :label="__('Description')" rows="3" />
        <div class="flex gap-2">
            <flux:button type="submit" variant="primary">{{ __('Create Template') }}</flux:button>
            <flux:button :href="route('admin.templates.index')" variant="ghost" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>
        </div>
    </form>
</div>
