<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('Pages')] class extends Component {
    //
};
?>

<div>
    <flux:heading size="xl" class="mb-6">{{ __('Pages') }}</flux:heading>
    <flux:text class="text-zinc-500">{{ __('Coming soon...') }}</flux:text>
</div>
