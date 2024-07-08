<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {

}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-6">
        <p>Discover page</p>
    </div>
</section>
