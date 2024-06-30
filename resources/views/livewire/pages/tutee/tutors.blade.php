<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Tutor;

new #[Layout('layouts.app')] class extends Component {
    public $tutors;

    public function mount(){
        $this->tutors = Tutor::all();
    }
}; ?>

<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-6">
        @foreach ($tutors as $tutor)
            <p>{{ $tutor->user->email }}</p>
        @endforeach
    </div>
</x-app-layout>
