<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.admin')] class extends Component {

}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto">
            <livewire:profile.update-password-form />
        </div>
    </div>
</section>

