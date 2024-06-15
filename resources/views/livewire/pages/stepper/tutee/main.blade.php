<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Institute;

new #[Layout('layouts.app')] class extends Component {
    public $count = 2;

    // General
    public $user_type = 'tutee';
    public $dates = [''];
    public $inputs = [];

    // Tutee
    public $grade_level = '';
    public $from = [];
    public $to = [];
    public $institute = [];

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
    }

    public function next_step()
    {
        $this->validate_status();
        $this->count++;
    }

    public function prev_step()
    {
        if ($this->count === 2) {
            return redirect()->route('stepper');
        } else {
            $this->count--;
        }
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
        }
    }
}; ?>

<div>
    @include('livewire.pages.stepper.body')
</div>
