<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Tutor;

new #[Layout('layouts.app')] class extends Component {
    public $count = 1;
    public $user_type = '';
    public $grade_level = '';
    public $test = '';

    public function next_step()
    {
        $this->validate_status();
        $this->count++;
    }

    public function validate_status()
    {
        if ($this->count === 1) {
            $this->validate([
                'user_type' => 'required',
            ]);
        }

        switch ($this->user_type) {
            case $this->user_type === 'tutee':
                if ($this->count === 2) {
                    $this->validate([
                        'grade_level' => 'required',
                    ]);
                } elseif ($this->count === 3) {
                    # code...
                }
                break;

            case $this->user_type === 'tutor':
                if ($this->count === 2) {
                    $this->validate([
                        'test' => 'required',
                    ]);
                } elseif ($this->count === 3) {
                    # code...
                }
                break;
        }
    }

    public function submit()
    {
        $user = User::find(Auth::id());
        $user->user_type = $this->user_type;
        $user->is_stepper = 0;
        $user->save();

        if ($user && $this->user_type === 'tutee') {
            Tutee::create([
                'user_id' => $user->id,
                'grade_level' => $this->grade_level,
            ]);

            return redirect()->route('dashboard');
        } elseif ($user && $this->user_type === 'tutor') {
            Tutor::create([
                'user_id' => $user->id,
                'test' => $this->test,
            ]);

            return redirect()->route('dashboard');
        }
    }
}; ?>

<div>

    {{ $count }}
    {{ $user_type }}

    @if ($count === 1)
        @include('livewire.pages.stepper.role')
    @endif

    @switch($user_type)
        @case('tutee')
            @if ($count === 2)
                @include('livewire.pages.stepper.tutee.form')
            @endif

            @if ($count === 3)
                Fields for tutee
            @endif
        @break

        @case('tutor')
            @if ($count === 2)
                @include('livewire.pages.stepper.tutor.form')
            @endif

            @if ($count === 3)
                Fields for tutor
            @endif
        @break

    @endswitch

    @switch($count)
        @case($count < 4)
            <button wire:click='next_step'>Next</button>
        @break

        @case($count === 4)
            <button wire:click='submit'>Submit</button>
        @break

        @default
    @endswitch

</div>
