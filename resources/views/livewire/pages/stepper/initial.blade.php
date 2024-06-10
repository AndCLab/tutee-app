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

    // General
    public $user_type = '';
    public $dates = [''];
    public $inputs = [];

    // Tutee
    public $grade_level = '';
    public $from = [];
    public $to = [];
    public $institute = [];

    // Tutor
    public $test = '';

    public function mount()
    {
        $this->dates = range(1990, 2024);
        $this->inputs = [0];
        $this->from = [''];
        $this->to = [''];
        $this->institute = [''];
    }

    public function add_institute()
    {
        if (count($this->inputs) <= 3) {
            $this->inputs[] = count($this->inputs);
            $this->from[] = ''; // Ensure from, to, and institute arrays are synchronized
            $this->to[] = '';
            $this->institute[] = '';
        }
    }

    public function remove_institute($index)
    {
        unset($this->inputs[$index]);
        unset($this->from[$index]);
        unset($this->to[$index]);
        unset($this->institute[$index]);

        $this->inputs = array_values($this->inputs);
        $this->from = array_values($this->from);
        $this->to = array_values($this->to);
        $this->institute = array_values($this->institute);
    }

    public function next_step()
    {
        $this->validate_status();
        $this->count++;
    }

    public function prev_step()
    {
        if ($this->count === 1) {
            session()->flash('error', 'You\'re at step 1 dipshyet');
        } else{
            $this->count--;
        }
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
                    $this->validate(
                        [
                            'from.*' => 'required|date',
                            'to.*' => 'required|date|after:from.*',
                            'institute.*' => 'required|max:200',
                            'grade_level' => 'required',
                        ],
                        [
                            'to.*.after' => 'The "to" date must be after the "from" date.',
                        ],
                    );
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
        // Update stepper to 0
        $user = User::find(Auth::id());
        $user->user_type = $this->user_type;
        $user->is_stepper = 0;
        $user->save();

        if ($user && $this->user_type === 'tutee') {
            $tutee = Tutee::create([
                'user_id' => $user->id,
                'grade_level' => $this->grade_level,
            ]);

            foreach ($this->inputs as $item) {
                Institute::create([
                    'tutee_id' => $tutee->id,
                    'from' => $this->from[$item],
                    'to' => $this->to[$item],
                    'institute' => $this->institute[$item],
                ]);
            }

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

    @if (session('error'))
        {{ session('error') }}
    @endif
    <div>
        Step {{ $count }}
    </div>
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
            <x-wui-button wire:click='prev_step' wire:loading.attr='disabled' wire:target='prev_step' neutral
                label="Back" />
            <x-wui-button wire:click='next_step' emerald label="Next" />
        @break

        @case($count === 4)
            <x-wui-button wire:click='submit' emerald label="Submit" />
        @break

        @default
    @endswitch

</div>
