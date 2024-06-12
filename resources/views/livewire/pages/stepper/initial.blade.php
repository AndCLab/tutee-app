<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    public $user_type = '';
    public $count = 1;

    public function next_step()
    {
        $this->validate([
            'user_type' => 'required',
        ]);

        if ($this->user_type == 'tutee') {
            $route = redirect()->route('stepper.tutee');
        } elseif ($this->user_type == 'tutor') {
            $route = redirect()->route('stepper.tutor');
        }
        return $route;
    }
}; ?>

<div>
    @include('livewire.pages.stepper.body')
</div>
