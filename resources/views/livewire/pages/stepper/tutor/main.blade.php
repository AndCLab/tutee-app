<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    public $count = 2;

    // General
    public $user_type = 'tutor';
    public $dates = [''];
    public $inputs = [];

    // Tutor

    public function mount()
    {
        $this->dates = range(1990, 2024);
        $this->inputs = [0];
        $this->from = [''];
        $this->to = [''];
        $this->work = [''];
    }

    public function add_work()
    {
        if (count($this->inputs) < 3) {
            $this->inputs[] = count($this->inputs);
            $this->from[] = ''; // Ensure from, to, and work arrays are synchronized
            $this->to[] = '';
            $this->work[] = '';
        } else {
            session()->flash('error-work', 'You cannot add more than 3');
        }
    }

    public function remove_work($index)
    {
        unset($this->inputs[$index]);
        unset($this->from[$index]);
        unset($this->to[$index]);
        unset($this->work[$index]);
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
        // code here...
    }

    public function submit()
    {
        // code here...
    }
}; ?>

<div>
    @include('livewire.pages.stepper.body')
</div>
