<?php

use App\Models\PageTemplate;
use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('Templates')] class extends Component {
    public function delete(int $id): void
    {
        PageTemplate::findOrFail($id)->delete();
    }

    public function with(): array
    {
        return [
            'templates' => PageTemplate::withCount(['fields', 'pages'])->get(),
        ];
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">{{ __('Templates') }}</flux:heading>
        <flux:button variant="primary" :href="route('admin.templates.create')" wire:navigate>
            {{ __('Create Template') }}
        </flux:button>
    </div>
    <table class="w-full text-left text-sm">
        <thead class="border-b border-zinc-200 dark:border-zinc-700">
            <tr>
                <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Name') }}</th>
                <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Slug') }}</th>
                <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Fields') }}</th>
                <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Pages') }}</th>
                <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse ($templates as $template)
                <tr wire:key="{{ $template->id }}">
                    <td class="py-3">{{ $template->name }}</td>
                    <td class="py-3 text-zinc-500">{{ $template->slug }}</td>
                    <td class="py-3">{{ $template->fields_count }}</td>
                    <td class="py-3">{{ $template->pages_count }}</td>
                    <td class="py-3">
                        <div class="flex gap-2">
                            <flux:button size="sm" :href="route('admin.templates.edit', $template)" wire:navigate>
                                {{ __('Edit') }}
                            </flux:button>
                            <flux:modal.trigger :name="'delete-'.$template->id">
                                <flux:button size="sm" variant="danger">{{ __('Delete') }}</flux:button>
                            </flux:modal.trigger>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-zinc-500">
                        {{ __('No templates yet.') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @foreach ($templates as $template)
        <flux:modal :name="'delete-'.$template->id" class="max-w-sm">
            <div class="space-y-4">
                <flux:heading size="lg">{{ __('Delete Template?') }}</flux:heading>
                <flux:text>{{ __('This cannot be undone.') }}</flux:text>
                <div class="flex gap-2 justify-end">
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>
                    <flux:button
                        variant="danger" 
                        wire:click="delete({{ $template->id }})"
                        x-on:click="$flux.modal('delete-{{ $template->id }}').close()"
                    >
                        {{ __('Delete') }}
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
