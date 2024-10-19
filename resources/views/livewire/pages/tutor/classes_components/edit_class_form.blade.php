<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use App\Models\Tutor;
use App\Models\Classes;
use App\Models\Schedule;
use App\Models\RecurringSchedule;
use App\Models\ClassRoster;
use App\Models\Registration;
use App\Models\Fields;
use WireUi\Traits\Actions;

new #[Layout('layouts.app')] class extends Component {
    use Actions;

    // get auth tutor
    public $tutor;
    public $class;
    public $storedSchedules;

    // class properties
    public string $class_name = '';
    public string $class_description = '';
    public string $class_type = '';
    public string $class_location = '';
    public string $class_category = '';
    public string $class_link = '';
    public $class_students;
    public $class_fee = 0;
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
    public $showRegistrationDate;
    public $showClassSchedule;
    public $customModal = false;

    public function mount(int $id)
    {
        $this->class = Classes::findOrFail($id);

        $this->getFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])
                                ->toArray();

        $this->class_name = $this->class->class_name;
        $this->class_description = $this->class->class_description;
        $this->class_type = $this->class->class_type;
        $this->class_category = $this->class->class_category;

        // $this->start_time = Carbon::parse($this->class->schedule->start_time)->format('H:i');
        // $this->end_time = Carbon::parse($this->class->schedule->end_time)->format('H:i');
        // $this->sched_initial_date = Carbon::parse($this->class->schedule->end_time)->format('H:i');

        if ($this->class->class_students) {
            $this->class_students = $this->class->class_students;
        }

        if ($this->class->class_type == 'virtual') {
            $this->class_link = $this->class->class_location;
        } elseif ($this->class->class_type == 'physical') {
            $this->class_location = $this->class->class_location;
        }

        $this->class_fee = $this->class->class_fee;
        $this->class_fields = json_decode($this->class->class_fields, true); //fields setter

        // for registration date
        if ($this->class->class_category == 'group' && $this->class->registration !== null) {
            $this->regi_start_date = $this->class->registration->start_date;
            $this->regi_end_date = $this->class->registration->end_date;
        }

        $this->interval_unit = 'once';
        $this->stop_repeating = 'never';

    }

    public function with(): array
    {
        $this->storedSchedules = RecurringSchedule::where('schedule_id', $this->class->schedule->id)->get();
        $payment_check = ClassRoster::where('class_id', $this->class->id)->where('proof_of_payment', '!=', null)->exists();

        return [
            'storedSchedules' => $this->storedSchedules,
            'payment_check' => $payment_check,
        ];
    }

    // START:CUSTOM SCHEDULE
    public function getCustomWeek($week)
    {
        $validWeeks = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        if (!in_array($week, $validWeeks)) {
            return;
        }

        if (($key = array_search($week, $this->frequency)) !== false) {
            unset($this->frequency[$key]);
        } else {
            $this->frequency[] = $week;
        }

        $this->regenerateDates();
    }

    public function updatedCustomEndDate($newEndDate)
    {
        // update the customEndDate
        $this->customEndDate = $newEndDate;

        // regenerate the dates after updating the end date
        $this->regenerateDates();
    }

    public function regenerateDates()
    {
        // ensure sched_initial_date and customEndDate are set
        if (isset($this->sched_initial_date) && isset($this->customEndDate)) {
            $startDate = Carbon::parse($this->sched_initial_date);
            $endDate = Carbon::parse($this->customEndDate);

            // clear previous dates
            $this->generatedDates = [];

            // get the day of the week for sched_initial_date
            // D is 'Mon', 'Tue'
            $startDayOfWeek = $startDate->format('D');

            // check if the initial date's day is part of the selected frequency
            // if the initial day is part of the frequency, include it in generated dates
            if (in_array($startDayOfWeek, $this->frequency)) {
                $this->generatedDates[] = $startDate->toDateString();
            }

            // generate dates based on the selected weekdays
            foreach ($this->frequency as $day) {
                // get the next occurrence of the day after the initial date
                $current = $startDate->copy()->next($day);

                while ($current <= $endDate) {
                    $this->generatedDates[] = $current->toDateString(); // store the date as a string
                    $current->addWeek(); // move to the same weekday in the next week
                }
            }

            sort($this->generatedDates);
        }
    }
    // END:CUSTOM SCHEDULE

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
    public function updateScheduleDate()
    {
        $tutor = Tutor::where('user_id', Auth::id())->first();

        $neverValue = false;

        if ($this->stop_repeating == 'never' && ($this->interval_unit != 'once' && $this->interval_unit != 'custom')) {
            $neverValue = true;
        }

        // schedule
        $scheduleData = [
            'start_time' => $this->start_time,
            'tutor_id' => $tutor->id,
            'end_time' => $this->end_time,
            'never_end' => $neverValue == true ? 1 : 0, // tutor will close this class manually if it sets to 1
        ];

        $schedule = null;

        // Check if the schedule already exists, excluding the current schedule ID
        $scheduleExists = Schedule::where('id', '!=', $this->class->schedule->id)
                                ->whereHas('recurring_schedule', function ($query) {
                                    $query->whereIn('dates', $this->generatedDates);
                                })
                                ->whereTime('start_time', '<=', $this->end_time)
                                ->whereTime('end_time', '>', $this->start_time)
                                ->whereHas('classes', function ($query) {
                                    $query->where('class_status', '!=', 0); // Exclude closed classes
                                })
                                ->exists();

        if (!$scheduleExists) {
            // find the existing schedule by class_id or another identifier
            $schedule = $this->class->schedule;

            if ($schedule) {
                // Update the schedule
                $schedule->update($scheduleData);

                // Delete old recurring schedules if they should be replaced
                RecurringSchedule::where('schedule_id', $schedule->id)->delete();

                // Insert the new recurring schedules
                foreach ($this->generatedDates as $date) {
                    RecurringSchedule::create([
                        'schedule_id' => $schedule->id,
                        'dates' => $date
                    ]);
                }
            }
        }

        return $schedule;
    }


    // helper method for notifications
    private function sendNotification($title, $description, $icon, $timeout = 2500)
    {
        $this->notification([
            'title' => $title,
            'description' => $description,
            'icon' => $icon,
            'timeout' => $timeout,
        ]);
    }

    public function editClass()
    {
        // Define validation rules
        $rules = [
            'class_name' => ['required', 'string', 'max:255'],
            'class_description' => ['required', 'string', 'max:255'],
            'class_fields' => ['required'],
            'class_location' => ['nullable', 'string', 'max:255'],
            'class_link' => ['nullable', 'string', 'max:255'],
        ];

        if ($this->sched_initial_date && $this->interval_unit) {
            // Schedule validation
            $rules['sched_initial_date'] = ['required', 'date'];

            if ($this->class->registration) {
                $rules['sched_initial_date'][] = 'after:regi_end_date';
            }

            $rules['sched_end_date'] = ['nullable', 'date', 'after:sched_initial_date'];
            $rules['start_time'] = ['required', 'date_format:H:i'];
            $rules['end_time'] = ['required', 'date_format:H:i', 'after:start_time'];

            // Recurrence and interval validation
            $rules['interval_unit'] = ['required', 'string', 'in:once,months,weeks,days,weekdays,custom'];
            $rules['stop_repeating'] = ['required', 'string', 'in:never,date'];
        }

        // Add conditional validation for group class with registration
        if ($this->class->class_category === 'group' && $this->class->registration) {
            $rules['class_students'] = ['required', 'integer', 'min:2', 'max:50'];
            $rules['regi_start_date'] = ['required', 'date'];
            $rules['regi_end_date'] = ['required', 'date', 'after:regi_start_date'];
        }


        // Validate inputs
        $this->validate($rules);

        // Get current fields
        $currentFields = json_decode($this->class->class_fields);

        // Update class details
        $this->class->class_name = $this->class_name;
        $this->class->class_description = $this->class_description;
        $this->class->class_fields = is_array($this->class_fields) ? json_encode($this->class_fields) : $this->class_fields;
        $this->class->class_students = $this->class_students;
        $this->class->class_fee = $this->class_fee;

        // Determine class type
        if ($this->class_location && $this->class_link) {
            $this->sendNotification('Error', 'Either virtual or physical class', 'error');
            return;
        } elseif ($this->class_link) {
            $this->class_type = 'virtual';
        } elseif ($this->class_location) {
            $this->class_type = 'physical';
        } else {
            $this->sendNotification('Error', 'Either virtual or physical class', 'error');
            return;
        }

        $this->class->class_location = $this->class_type == 'virtual' ? $this->class_link : $this->class_location;

        // Update schedule
        if ($this->sched_initial_date && $this->interval_unit) {
            $schedule = $this->updateScheduleDate();

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
        }

        // Update registration dates if applicable
        if ($this->class->class_category == 'group' && $this->class->registration) {
            $this->class->registration->start_date = $this->regi_start_date;
            $this->class->registration->end_date = $this->regi_end_date;
            $this->class->registration->save();
        }

        $newFields = json_decode($this->class->class_fields);

        // Decrement if current fields are not in the new fields
        foreach ($currentFields as $currentField) {
            $fields = Fields::where('user_id', Auth::id())
                            ->where('active_in', Auth::user()->user_type)
                            ->where('field_name', $currentField)
                            ->get();

            if (!in_array($currentField, $newFields)) {
                foreach ($fields as $field) {
                    $field->class_count--;
                    $field->save();
                }
            }
        }

        // Increment if new fields are not in the current fields
        foreach ($newFields as $newField) {
            $fields = Fields::where('user_id', Auth::id())
                            ->where('active_in', Auth::user()->user_type)
                            ->where('field_name', $newField)
                            ->get();

            if (!in_array($newField, $currentFields)) {
                foreach ($fields as $field) {
                    $field->class_count++;
                    $field->save();
                }
            }
        }

        // reset states
        $this->customModal = false;
        $this->interval_unit = 'once';
        $this->stop_repeating = 'never';
        $this->generatedDates = [];

        // Save the class
        $this->class->save();

        // Notify user of success
        $this->sendNotification('Updated Class!', 'Successfully updated!', 'success');
    }

}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">

        {{-- breadcrumb --}}
        <nav class="flex justify-between">
            <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
                <li class="flex items-center h-full">
                    <a href="{{ route('classes') }}" wire:navigate class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z" fill="currentColor"></path></svg>
                        <span>Classes</span>
                    </a>
                </li>
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none" stroke="none"><path d="M10 8.013l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>
                <li>
                    <a class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                        {{ $this->class_name }}
                    </a>
                </li>
            </ol>
        </nav>

        {{-- form --}}
        <form wire:submit='editClass'>
            <div class="space-y-2 md:space-y-0 md:inline-flex my-4 justify-between items-center w-full">
                <p class="capitalize font-semibold text-xl">Edit {{ $this->class_name }}</p>

                <div class="inline-flex gap-2 items-center">
                    <x-primary-button class="text-xs" type='submit' wireTarget="editClass">
                        Update Class
                    </x-primary-button>
                </div>
            </div>
            <div class="lg:grid lg:grid-cols-2 items-start gap-5 lg:space-y-0 space-y-3">
                {{-- general info --}}
                <div class="space-y-3">
                    <div class="space-y-3">
                        <div class="{{ $this->class_category == "group" ? 'grid grid-cols-2 gap-4' : '' }}">

                            {{-- class name --}}
                            <x-wui-input wire:model='class_name' label="Class Name" placeholder='Enter class name' shadowless/>

                            {{-- class students --}}
                            @if ($this->class_category == "group")
                                <x-wui-inputs.number wire:model='class_students' label="How many students?" shadowless/>
                            @endif
                        </div>

                        {{-- class description --}}
                        <div>
                            <x-wui-textarea wire:model='class_description' label="Class Description" class="resize-none" placeholder='Enter class description' shadowless/>
                        </div>

                        <div class="flex gap-2 items-center justify-between w-full">

                            @if ($this->class_category == "group")
                                {{-- class registration --}}
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Class Registration
                                        </label>
                                    </div>
                                    <x-wui-button label="Class Registration"
                                        flat
                                        :negative="$errors->has('regi_start_date') ||
                                                    $errors->has('regi_end_date')"
                                        :emerald="!$errors->has('regi_start_date') ||
                                                    !$errors->has('regi_end_date')"
                                        sm
                                        :icon="!$errors->has('regi_start_date') &&
                                                !$errors->has('regi_end_date') ? 'calendar' : 'exclamation-circle' "
                                        wire:click="$set('showRegistrationDate', true)"
                                    />
                                </div>
                            @endif

                            {{-- class schedule --}}
                            <div>
                                <div class="flex justify-between mb-1">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Class Schedule
                                    </label>
                                </div>
                                <x-wui-button label="Class Schedule"
                                    flat
                                    :negative="$errors->has('sched_initial_date') ||
                                                $errors->has('sched_end_date') ||
                                                $errors->has('start_time') ||
                                                $errors->has('end_time') ||
                                                $errors->has('interval_units')"
                                    :emerald="!$errors->has('sched_initial_date') ||
                                                !$errors->has('sched_end_date') ||
                                                !$errors->has('start_time') ||
                                                !$errors->has('end_time') ||
                                                !$errors->has('interval_units')"
                                    sm
                                    :icon="!$errors->has('sched_initial_date') &&
                                            !$errors->has('sched_end_date') &&
                                            !$errors->has('start_time') &&
                                            !$errors->has('end_time') &&
                                            !$errors->has('interval_units') ? 'calendar' : 'exclamation-circle' "
                                    wire:click="$set('showClassSchedule', true)"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        {{-- class fields --}}
                        <div>
                            <x-wui-select
                                wire:model="class_fields"
                                label="Class Fields"
                                placeholder="Select fields"
                                multiselect
                                shadowless
                            >
                                @foreach ($getFields as $field)
                                    <x-wui-select.option
                                        label="{{ $field['field_name'] }}"
                                        value="{{ $field['field_name'] }}"
                                    />
                                @endforeach
                            </x-wui-select>
                        </div>

                        {{-- class price --}}
                        <div>
                            <x-wui-inputs.currency :disabled="$payment_check" wire:model='class_fee' label='Class Fee' icon="cash" placeholder="Enter class price" shadowless/>
                        </div>
                    </div>

                    {{-- Virtual or Physical Class --}}
                    <div class="flex flex-col gap-4" x-data="{ tab: window.location.hash ? window.location.hash : '#virtual' }">
                        {{-- Left panel --}}
                        <ul class="flex bg-[#F1F5F9] px-1.5 py-1.5 gap-2 rounded-lg">
                            <li class="w-full text-center">
                                <a :class="tab !== '#virtual' ? '' : 'bg-white'"
                                    class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out"
                                    x-on:click.prevent="tab='#virtual'"> Virtual Class </a>
                            </li>
                            <li class="w-full">
                                <a :class="tab !== '#physical' ? '' : 'bg-white'"
                                    class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out"
                                    x-on:click.prevent="tab='#physical'"> Physical Class </a>
                            </li>
                        </ul>

                        {{-- Right panel --}}
                        <div>
                            <div x-show="tab == '#virtual'" x-cloak>
                                <div class="w-full">
                                    <x-wui-input wire:model='class_link' label="Virtual Session" placeholder="Enter virtual link" shadowless/>
                                </div>
                            </div>

                            <div x-show="tab == '#physical'" x-cloak>
                                <div class="w-full">
                                    <x-wui-input wire:model='class_location' label="Class Venue" placeholder='Enter class venue' shadowless/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="font-semibold">Current Schedules in this Class</p>
                    <span class="text-sm">
                        {{ Carbon::create($class->schedule->start_time)->format('g:i A') }} -
                        {{ Carbon::create($class->schedule->end_time)->format('g:i A') }}
                    </span>
                    <div class="gap-2 space-y-2">
                        @foreach ($storedSchedules as $date)
                            <x-wui-badge flat info label="{{ Carbon::create($date->dates)->format('l, F j, Y') }}" />
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>

    @include('livewire.pages.tutor.classes_components.schedule_modal')
    @include('livewire.pages.tutor.classes_components.register_modal')

    <x-wui-notifications position="bottom-right" />

</section>


