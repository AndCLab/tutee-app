<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Classes;
use App\Models\ClassRoster;
use App\Models\Fields;

new #[Layout('layouts.app')] class extends Component {

    public $class_roster;
    public $total_students;
    public $class;

    public $cr_id;

    public function mount(int $id)
    {
        $this->cr_id = $id;
        $this->class = Classes::findOrFail($id);
        $this->class_roster = ClassRoster::where('class_id', $id)->first();
        $this->total_students = ClassRoster::where('class_id', $id)->count();
    }

}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">

        <div class="md:grid md:grid-row items-start pb-3 mb-4">
            <p class="capitalize font-semibold text-xl">List of Attendees</p>
            <p class="capitalize text-sm text-[#0F172A]">Class Name: {{ $class->class_name }}</p>
            <p class="capitalize text-sm text-[#0F172A]">Schedule:
                {{ Carbon::create($class->schedule->start_time)->format('g:i A') }} -
                {{ Carbon::create($class->schedule->end_time)->format('g:i A l') }}
            </p>
            <p class="capitalize text-sm text-[#0F172A]">Total Students: {{ $total_students }}</p>
        </div>
        <livewire:class-roster-table :$cr_id />
    </div>
</section>
