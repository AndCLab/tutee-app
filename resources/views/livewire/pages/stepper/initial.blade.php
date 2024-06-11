<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Institute;
use App\Models\Tutor;

new #[Layout('layouts.app')] class extends Component {
    public $count = 1;
    public $user_type = '';

    public function next_step()
    {
        $this->count++;
    }

    public function prev_step()
    {
        $this->count--;
    }


}; ?>

<div>
    <div class="flex justify-center items-center h-screen">

        <div class="grid grid-cols-4 grid-flow-col gap-10">
            <div>
                @include('livewire.pages.stepper.header')
            </div>
            <div class="col-span-4">
                @if ($count === 1)
                    @include('livewire.pages.stepper.role')
                @endif

                @switch($user_type)
                    @case('tutee')
                        @if ($count === 2)
                            {{-- @livewire('pages.stepper.tutee.steps.form') --}}
                            Forms for tutee
                        @endif

                        @if ($count === 3)
                            Fields for tutee
                        @endif
                    @break

                    @case('tutor')
                        @if ($count === 2)
                            {{-- @livewire('pages.stepper.tutor.steps.form') --}}
                            Forms for tutor
                        @endif

                        @if ($count === 3)
                            Fields for tutor
                        @endif
                    @break

                @endswitch

                @switch($count)
                    @case($count < 4)
                        @if ($count == 2)
                            <x-wui-button wire:click='prev_step' wire:loading.attr='disabled' wire:target='prev_step' neutral label="Back" />
                        @endif
                        <x-wui-button wire:click='next_step' emerald label="Next" />
                    @break

                    @case($count === 4)
                        <x-wui-button wire:click='submit' emerald label="Submit" />
                    @break

                    @default
                @endswitch
            </div>
        </div>
    </div>


</div>
