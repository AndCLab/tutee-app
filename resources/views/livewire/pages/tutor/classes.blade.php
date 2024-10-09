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
    public $sched_end_date;

    public $start_time;
    public $end_time;

    public $interval_unit; // days, weeks, months
    public $stop_repeating;

    // custom
    public $frequency = [];
    public $customEndDate;

    public $generatedDates = []; // recurring schedules

    // for registration date
    public $regi_start_date;
    public $regi_end_date;

    // states
    public $IndiClassFeeToggle;
    public $GroupClassFeeToggle;
    public $showRegistrationDate;
    public $showClassSchedule;
    public $customModal = false;

    public function mount()
    {
        $this->getFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])->toArray();
        $this->interval_unit = 'once';
        $this->stop_repeating = 'never';
    }

    public function getCustomWeek($week)
    {
        // Define valid week days
        $validWeeks = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        // Check if the provided week is valid
        if (!in_array($week, $validWeeks)) {
            return; // Exit if invalid week
        }

        // Toggle the week in the frequency array
        if (($key = array_search($week, $this->frequency)) !== false) {
            // If it is present, remove it
            unset($this->frequency[$key]);
        } else {
            // If not present, add it to the frequency array
            $this->frequency[] = $week;
        }

        // Clear previous dates
        $this->generatedDates = [];

        // Ensure sched_initial_date and customEndDate are set
        if (isset($this->sched_initial_date) && isset($this->customEndDate)) {
            $startDate = Carbon::parse($this->sched_initial_date);
            $endDate = Carbon::parse($this->customEndDate);

            // Generate dates based on the selected weekdays
            foreach ($this->frequency as $day) {
                // Get the next occurrence of the day
                $current = $startDate->copy()->next($day);

                while ($current <= $endDate) {
                    $this->generatedDates[] = $current->toDateString(); // Store the date as a string
                    $current->addWeek(); // Move to the same weekday in the next week
                }
            }
        }
    }

    public function updated($propertyName)
    {
        if ($this->interval_unit != 'custom') {
            $this->customEndDate = null;
            $this->frequency = [];
            $this->generatedDates = [];
        }

        // validate only the updated field
        $this->validateOnly($propertyName, [
            'sched_initial_date' => ['required', 'date'],
            'sched_end_date' => ['nullable', 'date', 'after:sched_initial_date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $startDate = Carbon::parse($this->sched_initial_date);
        $endDate = Carbon::parse($this->sched_end_date);

        // if interval unit is "once" then clear all input fields
        if ($this->interval_unit == 'once') {
            $this->stop_repeating = 'never';
            $this->generatedDates = [$startDate];
            $this->sched_end_date = null;

            return;
        }

        // if interval unit is "custom" then clear all input fields
        if ($this->interval_unit == 'custom') {
            $this->stop_repeating = 'never';
            $this->sched_end_date = null;

            $this->customModal = true;

            return;
        }

        // if stop repeating is "never" then clear all input fields
        if ($this->stop_repeating == 'never') {
            $this->generatedDates = [$startDate];
            $this->sched_end_date = null;

            return;
        }

        if ($this->interval_unit && $this->sched_end_date) {
            $diffDays = $this->calculateDiff($startDate, $endDate);

            if ($startDate->lessThan($endDate)) {
                for ($i = 0; $i <= $diffDays; $i++) {
                    $loopDate = $this->getNextDate($startDate);

                    if ($loopDate) {
                        $this->generatedDates[] = $loopDate->format('Y-m-d');
                    }

                    $startDate = $this->incrementDate($startDate);
                }
            }
        }
    }

    // calculate the difference between dates based on the interval unit.
    private function calculateDiff(Carbon $startDate, Carbon $endDate)
    {
        switch ($this->interval_unit) {
            case 'once':
                return 0; // no iterations needed
            case 'weeks':
                return $startDate->diffInWeeks($endDate);
            case 'months':
                return $startDate->diffInMonths($endDate);
            default: // days
                return $startDate->diffInDays($endDate);
        }
    }

    // get the next date to be added to the generated list.
    private function getNextDate(Carbon $date)
    {
        if ($this->interval_unit == 'weekdays' && $date->isWeekend()) {
            return null; // skip weekends for 'weekdays'
        }

        // return if the date is not weekend
        return $date;
    }

    // increment the start date based on the interval unit.
    private function incrementDate(Carbon $date)
    {
        if ($this->interval_unit == 'weekdays') {
            return $this->skipWeekends($date->copy()->addDay());
        }

        return $date->add(1, $this->interval_unit);
    }

    // skip weekends for 'weekdays' interval.
    private function skipWeekends(Carbon $date)
    {
        while ($date->isWeekend()) {
            $date->addDay();
        }

        return $date;
    }

    // schedule db insertion
    public function scheduleDate()
    {
        $tutor = Tutor::where('user_id', Auth::id())->first();

        // schedule
        $scheduleData = [
            'start_time' => $this->start_time,
            'tutor_id' => $tutor->id,
            'end_time' => $this->end_time,
            'never_end' => $this->stop_repeating == 'never' ? 1 : 0, // tutor will close this class manually if it sets to 1
        ];

        $schedule = null;

        // check if the schedule already exists
        $scheduleExists = Schedule::where('tutor_id', $tutor->id)
                                    ->whereHas('recurring_schedule', function ($query) {
                                        $query->whereIn('dates', $this->generatedDates);
                                    })
                                    ->whereTime('start_time', '<=', $this->end_time)
                                    ->whereTime('end_time', '>', $this->start_time)
                                    ->exists();

        if (!$scheduleExists) {
            // create the schedule
            $schedule = Schedule::create($scheduleData);

            // add recurring dates
            foreach ($this->generatedDates as $date) {
                RecurringSchedule::create([
                    'schedule_id' => $schedule->id,
                    'dates' => $date
                ]);
            }

        }

        return $schedule;
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

            // schedules
            'sched_initial_date' => ['required', 'date'],
            'sched_end_date' => ['nullable', 'date', 'after:sched_initial_date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],

            // recurrence and interval
            'interval_unit' => ['required', 'string', 'in:once,months,weeks,days,weekdays,custom'],
            'stop_repeating' => ['required', 'string', 'in:never,date'],
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

        $schedule = $this->scheduleDate();

        if ($schedule == null) {
            // notify the user that the schedule already exists
            $this->notification([
                'title'       => 'Schedule must be unique',
                'description' => 'You have chosen this schedule from one of your classes',
                'icon'        => 'error',
                'timeout'     => 2500,
            ]);

            return;
        }

        $classFieldsJson = is_array($this->class_fields) ? json_encode($this->class_fields) : $this->class_fields;

        Classes::create([
            'tutor_id' => $tutor->id,
            'class_name' => $this->class_name,
            'class_description' => $this->class_description,
            'class_fields' => $classFieldsJson,
            'class_type' => $this->class_type,
            'class_category' => 'individual',
            'class_location' => $this->class_type == 'virtual' ? $this->class_link : $this->class_location,
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
            'sched_end_date',
            'start_time',
            'end_time',

            'regi_start_date',
            'regi_end_date',
        );

        $this->customModal = false;
        $this->interval_unit = 'once';
        $this->stop_repeating = 'never';
        $this->generatedDates = [];

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

            // schedules
            'sched_initial_date' => ['required', 'date'],
            'sched_end_date' => ['nullable', 'date', 'after:sched_initial_date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],

            // recurrence and interval
            'interval_unit' => ['required', 'string', 'in:once,months,weeks,days,weekdays,custom'],
            'stop_repeating' => ['required', 'string', 'in:never,date'],

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

        $schedule = $this->scheduleDate();

        if ($schedule == null) {
            // notify the user that the schedule already exists
            $this->notification([
                'title'       => 'Schedule must be unique',
                'description' => 'You have chosen this schedule from one of your classes',
                'icon'        => 'error',
                'timeout'     => 2500,
            ]);

            return;
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
            'class_location' => $this->class_type == 'virtual' ? $this->class_link : $this->class_location,
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
            'sched_end_date',
            'start_time',
            'end_time',
            'interval_unit',
            'stop_repeating',

            'regi_start_date',
            'regi_end_date',
        );

        $this->customModal = false;
        $this->interval_unit = 'once';
        $this->stop_repeating = 'never';
        $this->generatedDates = [];

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
