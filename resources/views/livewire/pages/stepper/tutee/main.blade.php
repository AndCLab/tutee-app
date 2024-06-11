<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Institute;
use App\Models\Tutor;

new #[Layout('layouts.app')] class extends Component {
    public $count;

    // General
    public $user_type = 'tutee';
    public $dates = [''];
    public $inputs = [];

    // Tutee
    public $grade_level = '';
    public $from = [];
    public $to = [];
    public $institute = [];

    #[On('prev_step')]
    public function mount($count)
    {
        $this->dates = range(1990, 2024);
        $this->inputs = [0];
        $this->from = [''];
        $this->to = [''];
        $this->institute = [''];
        $this->count = $count;
    }

    public function add_institute()
    {
        if (count($this->inputs) < 3) {
            $this->inputs[] = count($this->inputs);
            $this->from[] = ''; // Ensure from, to, and institute arrays are synchronized
            $this->to[] = '';
            $this->institute[] = '';
        } else {
            session()->flash('error-institute', 'You cannot add more than 3');
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

    public function validate_status()
    {
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
    }

    public function submit()
    {
        // Update stepper to 0
        $user = User::find(Auth::id());
        $user->user_type = $this->user_type;
        $user->is_stepper = 0;
        $user->save();

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
    }
}; ?>

<div>

    @if (session('error'))
        {{ session('error') }}
    @endif

    @if ($count === 2)
        @include('livewire.pages.stepper.tutee.steps.form')
    @endif

    @if ($count === 3)
        Fields for tutee
    @endif

</div>
