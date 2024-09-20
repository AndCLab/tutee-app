<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Actions\Logout;

new #[Layout('layouts.app')] class extends Component {
    public $user_type = '';
    public $count;
    public $confirm;

    public $title = 'Stepper | Role Selection';

    public function mount()
    {
        $this->count = 1;
    }

    public function next_step()
    {
        $this->validate([
            'user_type' => 'required',
        ]);

        if ($this->user_type == 'tutee') {
            return redirect()->route('stepper.tutee');
        } elseif ($this->user_type == 'tutor') {
            return redirect()->route('stepper.tutor');
        }
    }


    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/login', navigate: true);
    }
}; ?>

@push('title')
    {{ $title }}
@endpush

<div>
    @include('livewire.pages.stepper.body')
</div>
