<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    public $user_type = '';
    public $count;

    public function mount()
    {
        $this->count = 1;
    }

    public function next_step()
    {
        $this->validate([
            'user_type' => 'required',
        ]);
        $this->count++;
    }
}; ?>

<div>
    <div class="mx-10">
        <div class="grid grid-rows-4 grid-flow-row md:grid-rows-1 md:grid-cols-8 md:grid-flow-col justify-center items-center h-screen max-w-5xl mx-auto">
            <div class="md:col-span-3">
                @include('livewire.pages.stepper.header')
            </div>
            <div class="md:col-span-5 row-span-4">
                @if ($count === 1)
                    @include('livewire.pages.stepper.role')
                    <div class="flex justify-between w-3/4 mx-auto gap-3">
                        <x-primary-button wire:click.prevent='next_step' class="w-full">
                            Next
                        </x-primary-button>
                    </div>
                @endif

                @if ($user_type == 'tutee')
                    @livewire('pages.stepper.tutee.main')
                @endif
            </div>
        </div>
    </div>
</div>
