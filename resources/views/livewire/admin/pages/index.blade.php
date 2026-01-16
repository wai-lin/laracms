<?php
use App\Models\Page;
use Livewire\Volt\Component;
use Livewire\Attributes\{Title, Url};

new #[Title('Pages')] class extends Component {
    #[Url]
    public string $status = '';

    public function delete(int $id): void
    {
        Page::findOrFail($id)->delete();
    }

    public function with(): array
    {
        $query = Page::with('template')->latest();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return [
            'pages' => $query->get(),
        ];
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">{{ __('Pages') }}</flux:heading>
        <flux:button variant="primary" :href="route('admin.pages.create')" wire:navigate>
            {{ __('Create Page') }}
        </flux:button>
    </div>
    <div class="mb-4">
        <flux:select wire:model.live="status" class="w-48">
            <flux:select.option value="">All Statuses</flux:select.option>
            <flux:select.option value="draft">Draft</flux:select.option>
            <flux:select.option value="published">Published</flux:select.option>
            <flux:select.option value="scheduled">Scheduled</flux:select.option>
        </flux:select>
    </div>
    <table class="w-full text-left text-sm">
        <thead class="border-b border-zinc-200 dark:border-zinc-700">
            <tr>
                <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Title') }}</th>
                <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Template') }}</th>
                <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Status') }}</th>
                <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Published At') }}</th>
                <th class="pb-3 font-medium text-zinc-500 dark:text-zinc-400">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse ($pages as $page)
            <tr wire:key="{{ $page->id }}">
                <td class="py-3">{{ $page->title }}</td>
                <td class="py-3 text-zinc-500">{{ $page->template?->name ?? '—' }}</td>
                <td class="py-3">
                    <flux:badge size="sm" :color="match($page->status) {
                                'published' => 'green',
                                'scheduled' => 'amber',
                                default => 'zinc'
                            }">
                        {{ ucfirst($page->status) }}
                    </flux:badge>
                </td>
                <td class="py-3 text-zinc-500">{{ $page->published_at?->format('M j, Y g:i A') ?? '—' }}</td>
                <td class="py-3">
                    <div class="flex gap-2">
                        <flux:button size="sm" :href="route('admin.pages.edit', $page)" wire:navigate>
                            {{ __('Edit') }}
                        </flux:button>
                        <flux:modal.trigger :name="'delete-'.$page->id">
                            <flux:button size="sm" variant="danger">{{ __('Delete') }}</flux:button>
                        </flux:modal.trigger>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-6 text-center text-zinc-500">
                    {{ __('No pages yet.') }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @foreach ($pages as $page)
        <flux:modal :name="'delete-'.$page->id" class="max-w-sm">
            <div class="space-y-4">
                <flux:heading size="lg">{{ __('Delete Page?') }}</flux:heading>
                <flux:text>{{ __('This cannot be undone.') }}</flux:text>
                <div class="flex gap-2 justify-end">
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>
                    <flux:button
                        variant="danger"
                        wire:click="delete({{ $page->id }})"
                        x-on:click="$flux.modal('delete-{{ $page->id }}').close()"
                    >
                        {{ __('Delete') }}
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>
