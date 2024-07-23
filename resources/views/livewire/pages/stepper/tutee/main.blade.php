<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Actions\Logout;
use Livewire\Attributes\Validate;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Institute;
use App\Models\Fields;
use WireUi\Traits\Actions;

new #[Layout('layouts.app')] class extends Component {
    use Actions;

    public $count = 2;

    // General
    public $user_type = 'tutee';
    public $dates = [''];
    public $inputs = [];
    public $i;
    public $specific = '';

    // Tutee
    public $gradeLevelList = Tutee::GRADE_LEVELS;
    public $grade_level = '';
    public $from = [];
    public $to = [];
    public $institute = [];
    public $selected = [];

    // Mount everything
    public function mount()
    {
        $this->i = 0;
        $this->inputs = [0];
        $this->from = [''];
        $this->to = [''];
        $this->institute = [''];
    }

    // Institute
    public function add_institute()
    {
        if (count($this->inputs) < 3) {
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

    // Fields
    public function get_field($name)
    {
        if (!in_array($name, $this->selected)) {
            $this->selected[$this->i++] = $name;
        }
    }

    // Fields: Specific Field
    public function get_specific_field()
    {
        $this->validate([
            'specific' => 'required'
        ]);

        if (!in_array($this->specific, $this->selected)) {
            $this->selected[$this->i++] = $this->specific;
        }

        $this->reset('specific');
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
                $this->notification([
                    'title'       => 'Error',
                    'description' => 'Must have at least 3 fields',
                    'icon'        => 'error',
                    'timeout'     => 2500,
                ]);
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
                    'from.*.required' => 'The field is required',
                    'to.*.required' => 'The field is required',
                    'institute.*.required' => 'The field is required',
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

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/login', navigate: true);
    }
}; ?>

<div>
    @include('livewire.pages.stepper.body')
</div>
