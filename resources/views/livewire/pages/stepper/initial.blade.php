<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Institute;
use App\Models\Tutor;

new #[Layout('layouts.app')] class extends Component {
    public $user_type = '';
    public $chosen = '';
    public $count = 1;

    public function choose_user_type()
    {
        $this->chosen = $this->user_type;

        if ($this->chosen == 'tutee') {
            return redirect()->route('tutee');
        } else {
            return redirect()->route('tutee');
        }
    }
}; ?>

<div>
    <div class="flex justify-center items-center h-screen">
        <div class="grid grid-cols-4 grid-flow-col gap-10">
            <div>
                @include('livewire.pages.stepper.header')
            </div>
            <div class="col-span-4">
                @include('livewire.pages.stepper.role')
                <x-wui-button wire:click='choose_user_type' emerald label="Next" />
            </div>
        </div>
    </div>
</div>
