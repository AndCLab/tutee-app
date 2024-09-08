<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Classes;
use App\Models\Fields;

new #[Layout('layouts.app')] class extends Component {

    public $schedules;

    public function mount()
    {
        $user = Auth::user();
        $tutor = Tutor::where('user_id', $user->id)->first();

        $this->schedules = Classes::where('tutor_id', $tutor->id)->get();
    }


}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">

        <div class="md:grid md:grid-row items-start gap-5 pb-3">
            <p class="capitalize font-semibold text-xl">Schedules</p>
        </div>

        <livewire:class-roster-table/>
        {{-- schedule card --}}
    </div>
</section>
