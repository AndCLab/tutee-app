<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {

}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">
        <div class="lg:grid lg:grid-cols-3 items-start gap-5">
            <div class="lg:col-span-2 space-y-3">
                <p class="capitalize font-semibold text-xl mb-9">interests</p>

                {{-- Posts created by Tutees--}}
                <livewire:pages.tutee.post_components.post_list>
            </div>

            <div>
                {{-- Upcoming Schedules --}}
                <livewire:pages.tutee.components.upcoming_sched>
            </div>
        </div>
    </div>
</section>
