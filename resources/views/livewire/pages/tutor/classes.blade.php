<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Carbon\Carbon;
use WireUi\Traits\Actions;
use App\Models\Tutor;
use App\Models\Classes;
use App\Models\Schedule;
use App\Models\RecurringSchedule;
use App\Models\Registration;
use App\Models\Fields;

new #[Layout('layouts.app')] class extends Component {
    use Actions;

    public $title = 'Classes | Tutee';

    // class properties
    public string $class_name = '';
    public string $class_description = '';
    public string $class_type = '';
    public string $class_location = '';
    public string $class_link = '';
    public $class_students;
    public $class_fee = 0;
    public $class_status;
    public $class_fields = []; //fields setter
    public $getFields = []; // fields getter

    // for class schedule date
    public $sched_initial_date;
    public $start_time;
    public $end_time;
    public int $interval;
    public $interval_unit;
    public $occurrences;
    public $frequency = 'once';
    public $generatedDates = [];

    // for registration date
    public $regi_start_date;
    public $regi_end_date;

    // states
    public $IndiClassFeeToggle;
    public $GroupClassFeeToggle;
    public $showRegistrationDate;
    public $showClassSchedule;

    public function mount()
    {
        $this->getFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])->toArray();
    }

    public function updated()
    {
        $this->generatedDates = [];
        $startDate = Carbon::parse($this->sched_initial_date);

        if ($this->frequency === 'once') {
            $this->generatedDates[] = $startDate->format('Y-m-d H:i:s');
        } else {
            $this->validate([
                // schedules
                'sched_initial_date' => ['required', 'date'],
                'start_time' => ['required', 'date_format:H:i'],
                'end_time' => ['required', 'date_format:H:i', 'after:start_time'],

                // recurrence and interval
                'interval' => ['nullable', 'integer', 'lt:10', 'required_with:sched_date'],
                'interval_unit' => ['nullable', 'string', 'in:months,weeks,days', 'required_with:sched_date', 'required_with:interval'],
                'occurrences' => ['nullable', 'integer', 'gt:interval', 'lt:60', 'required_with:sched_date', 'required_with:interval', 'required_with:interval_unit'],
            ]);
            for ($i = 0; $i < $this->occurrences; $i++) {
                $this->generatedDates[] = $startDate->copy()->format('Y-m-d H:i:s');
                $startDate->add($this->interval, $this->interval_unit);
            }
        }
    }

    // individual class validation and creation
    public function IndividualValidation()
    {
        $this->validate([
            // class details
            'class_name' => ['required', 'string', 'max:255'],
            'class_description' => ['required', 'string', 'max:255'],
            'class_fields' => ['required'],
            'class_location' => ['string', 'max:255'],
            'class_link' => ['string', 'max:255'],
        ]);
    }

    public function createIndividualClass()
    {
        $tutor = Tutor::where('user_id', Auth::id())->first();

        $this->IndividualValidation();

        if ($this->class_location && $this->class_link) {
            $this->notification([
                'title'       => 'Error',
                'description' => 'Either virtual or physical class',
                'icon'        => 'error',
                'timeout'     => 2500,
            ]);

            return;
        } else if ($this->class_link) {
            $this->class_type = 'virtual';
            $this->class_location = $this->class_link;
        } else if ($this->class_location) {
            $this->class_type = 'physical';
        } else {
            $this->notification([
                'title'       => 'Error',
                'description' => 'Either virtual or physical class',
                'icon'        => 'error',
                'timeout'     => 2500,
            ]);

            return;
        }

        $scheduleData = [
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'frequency' => $this->frequency,
        ];

        if ($this->frequency != 'once') {
            $scheduleData['interval'] = $this->interval;
            $scheduleData['interval_unit'] = $this->interval_unit;
            $scheduleData['occurrences'] = $this->occurrences;
        }

        $schedule = Schedule::create($scheduleData);

        foreach ($this->generatedDates as $date) {
            RecurringSchedule::create([
                'schedule_id' => $schedule->id,
                'dates' => $date
            ]);
        }

        $classFieldsJson = is_array($this->class_fields) ? json_encode($this->class_fields) : $this->class_fields;

        $new_class = Classes::create([
            'tutor_id' => $tutor->id,
            'class_name' => $this->class_name,
            'class_description' => $this->class_description,
            'class_fields' => $classFieldsJson,
            'class_type' => $this->class_type,
            'class_category' => 'individual',
            'class_location' => $this->class_location,
            'class_fee' => $this->class_fee,
            'class_status' => 1,
            'schedule_id' => $schedule->id
        ]);


        // find the chosen fields and increment the class_count each of them fields.
        foreach ($this->class_fields as $value) {
            $fields = Fields::where('user_id', Auth::id())
                            ->where('active_in', Auth::user()->user_type)
                            ->where('field_name', $value)
                            ->get();

            foreach ($fields as $field) {
                $field->class_count = $field->class_count + 1;
                $field->save();
            }
        }

        $this->dispatch('new-class', isNotEmpty: 0);

        $this->reset(
            'class_name',
            'class_description',
            'class_fields',
            'class_location',
            'class_students',
            'class_fee',
            'class_link',

            'sched_initial_date',
            'start_time',
            'end_time',
            'interval',
            'interval_unit',
            'occurrences',

            'regi_start_date',
            'regi_end_date',
        );

        $this->notification([
            'title'       => 'Fresh Class!',
            'description' => 'Successfully created!',
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);

    }

     // group class validation and creation
     public function GroupValidation()
    {
        $this->validate([
            // class details
            'class_name' => ['required', 'string', 'max:255'],
            'class_description' => ['required', 'string', 'max:255'],
            'class_fields' => ['required'],
            'class_location' => ['string', 'max:255'],
            'class_link' => ['string', 'max:255'],
            'class_students' => ['required', 'lt:40', 'gt:2'],

            // registration
            'regi_start_date' => ['required', 'date'],
            'regi_end_date' => ['required', 'date', 'after:regi_start_date'],
        ]);
    }

    public function createGroupClass()
    {
        $tutor = Tutor::where('user_id', Auth::id())->first();

        $this->GroupValidation();

        if ($this->class_location && $this->class_link) {
            $this->notification([
                'title'       => 'Error',
                'description' => 'Either virtual or physical class',
                'icon'        => 'error',
                'timeout'     => 2500,
            ]);

            return;
        } else if ($this->class_link) {
            $this->class_type = 'virtual';
            $this->class_location = $this->class_link;
        } else if ($this->class_location) {
            $this->class_type = 'physical';
        } else {
            $this->notification([
                'title'       => 'Error',
                'description' => 'Either virtual or physical class',
                'icon'        => 'error',
                'timeout'     => 2500,
            ]);

            return;
        }

        $scheduleData = [
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'frequency' => $this->frequency,
        ];

        if ($this->frequency != 'once') {
            $scheduleData['interval'] = $this->interval;
            $scheduleData['interval_unit'] = $this->interval_unit;
            $scheduleData['occurrences'] = $this->occurrences;
        }

        $schedule = Schedule::create($scheduleData);

        foreach ($this->generatedDates as $date) {
            RecurringSchedule::create([
                'schedule_id' => $schedule->id,
                'dates' => $date
            ]);
        }

        $registration = Registration::create([
            'start_date' => $this->regi_start_date,
            'end_date' => $this->regi_end_date,
        ]);

        $classFieldsJson = is_array($this->class_fields) ? json_encode($this->class_fields) : $this->class_fields;

        Classes::create([
            'tutor_id' => $tutor->id,
            'class_name' => $this->class_name,
            'class_description' => $this->class_description,
            'class_fields' => $classFieldsJson,
            'class_type' => $this->class_type,
            'class_category' => 'group',
            'class_location' => $this->class_location,
            'class_students' => $this->class_students,
            'class_fee' => $this->class_fee,
            'class_status' => 1,
            'schedule_id' => $schedule->id,
            'registration_id' => $registration->id
        ]);

        foreach ($this->class_fields as $value) {
            $fields = Fields::where('user_id', Auth::id())
                            ->where('active_in', Auth::user()->user_type)
                            ->where('field_name', $value)
                            ->get();

            foreach ($fields as $field) {
                $field->class_count = $field->class_count + 1;
                $field->save();
            }
        }

        $this->dispatch('new-class', isNotEmpty: 0);

        $this->reset(
            'class_name',
            'class_description',
            'class_fields',
            'class_location',
            'class_students',
            'class_fee',
            'class_link',

            'sched_initial_date',
            'start_time',
            'end_time',
            'interval',
            'interval_unit',
            'occurrences',

            'regi_start_date',
            'regi_end_date',
        );

        $this->notification([
            'title'       => 'Fresh Class!',
            'description' => 'Successfully created!',
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);
    }

}; ?>

@push('title')
    {{ $title }}
@endpush

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">
        <div class="lg:grid lg:grid-cols-3 items-start gap-5">

            {{-- Class List --}}
            <div class="lg:col-span-2 space-y-3">
                <livewire:pages.tutor.classes_components.class_list>
            </div>

            {{-- Create Class --}}
            <div class="hidden lg:block space-y-3 sticky top-[5rem] overflow-y-auto max-h-[85vh] soft-scrollbar px-2 pb-3">

                {{-- Header --}}
                <p class="capitalize font-semibold text-xl mb-9">create class</p>

                {{-- Individual or Group Class --}}
                <div class="flex flex-col gap-4" x-data="{ tab: window.location.hash ? window.location.hash : '#indi' }">
                    {{-- Left panel --}}
                    <ul class="flex bg-[#F1F5F9] px-1.5 py-1.5 gap-2 rounded-lg">
                        <li class="w-full text-center">
                            <a :class="tab !== '#indi' ? '' : 'bg-white'"
                                class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out"
                                x-on:click.prevent="tab='#indi'"> Individual Class </a>
                        </li>
                        <li class="w-full">
                            <a :class="tab !== '#group' ? '' : 'bg-white'"
                                class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out"
                                x-on:click.prevent="tab='#group'"> Group Class </a>
                        </li>
                    </ul>

                    {{-- Right panel --}}
                    <div>
                        <div x-show="tab == '#indi'" x-cloak>
                            <div class="max-w-xl">
                                @include('livewire.pages.tutor.classes_components.individual')
                            </div>
                        </div>

                        <div x-show="tab == '#group'" x-cloak>
                            <div class="max-w-xl">
                                @include('livewire.pages.tutor.classes_components.group')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.pages.tutor.classes_components.schedule_modal')
    @include('livewire.pages.tutor.classes_components.register_modal')

    <x-wui-notifications position="bottom-right" />
</section>
