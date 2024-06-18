<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Institute;
use App\Models\Fields;

new #[Layout('layouts.app')] class extends Component {
    public $count = 2;

    // General
    public $user_type = 'tutee';
    public $dates = [''];
    public $inputs = [];
    public $i = 0;

    public $fields = [
        'English' => ['Grammar', 'Literature', 'Poetry', 'Writing'],
        'Mathematics' => ['Algebra', 'Geometry', 'Calculus', 'Statistics', 'Trigonometry'],
        'Science' => ['Biology', 'Chemistry', 'Physics', 'Astronomy', 'Geology'],
        'History' => ['Ancient', 'Medieval', 'Modern', 'World Wars', 'American'],
        'Geography' => ['Maps', 'Climate', 'Continents', 'Oceans', 'Countries'],
        'Computer Science' => ['Programming', 'Algorithms', 'Data Structures', 'Databases', 'Networks'],
        'Art' => ['Painting', 'Sculpture', 'Drawing', 'Photography', 'Film'],
        'Music' => ['Theory', 'Composition', 'Performance', 'Genres', 'History'],
        'Physical Education' => ['Sports', 'Exercise', 'Health', 'Fitness', 'Nutrition'],
    ];

    // Tutee
    public $grade_level = '';
    public $from = [];
    public $to = [];
    public $institute = [];
    public $selected = [];

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

        $this->inputs = array_values($this->inputs);
        $this->from = array_values($this->from);
        $this->to = array_values($this->to);
        $this->institute = array_values($this->institute);
    }

    // Fields

    public function get_field($name)
    {
        if (!in_array($name, $this->selected)) {
            $this->selected[$this->i++] = $name;
        }
    }

    public function remove_field($index)
    {
        unset($this->selected[$index]);
        $this->selected = array_values($this->selected);
    }

    public function next_step()
    {
        if ($this->count === 3) {
            if (count($this->selected) < 3) {
                session()->flash('error-field', 'Choose at least 3 fields');
            } else {
                $this->count++;
            }
        } else{
            $this->validate_status();
            $this->count++;
        }
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

            foreach ($this->selected as $item) {
                Fields::create([
                    'user_id' => $user->id,
                    'field_name' => $item,
                ]);
            }

            return redirect()->route('dashboard');
        }
    }
}; ?>

<div>
    @include('livewire.pages.stepper.body')
</div>
