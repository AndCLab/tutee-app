<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use App\Models\Tutor;
use App\Models\Classes;
use App\Models\Schedule;
use App\Models\RecurringSchedule;
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

    public function mount(int $id)
    {
        $this->class = Classes::findOrFail($id);
        $this->storedSchedules = RecurringSchedule::where('schedule_id', $this->class->schedule->id)->get();

        $this->getFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])
                                ->toArray();

        $this->class_name = $this->class->class_name;
        $this->class_description = $this->class->class_description;
        $this->class_type = $this->class->class_type;
        $this->class_category = $this->class->class_category;

        $this->start_time = Carbon::parse($this->class->schedule->start_time)->format('H:i');
        $this->end_time = Carbon::parse($this->class->schedule->end_time)->format('H:i');


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

    }

    public function updated()
    {
        $this->generatedDates = [];
        $startDate = Carbon::parse($this->sched_initial_date);

        if ($this->frequency === 'once') {
            $this->generatedDates[] = $startDate->format('Y-m-d H:i:s');
        } else {
            for ($i = 0; $i < $this->occurrences; $i++) {
                $this->generatedDates[] = $startDate->copy()->format('Y-m-d H:i:s');
                $startDate->add($this->interval, $this->interval_unit);
            }
        }
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

            // schedules
            'sched_initial_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],

            // recurrence and interval
            'interval' => ['nullable', 'integer', 'lt:10', 'required_with:sched_date'],
            'interval_unit' => ['nullable', 'string', 'in:months,weeks,days', 'required_with:sched_date', 'required_with:interval'],
            'occurrences' => ['nullable', 'integer', 'gt:interval', 'lt:60', 'required_with:sched_date', 'required_with:interval', 'required_with:interval_unit'],
        ];

        // Add conditional validation rules
        if ($this->class->class_category == 'group' && $this->class->registration) {
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
            $this->class_location = $this->class_link;
        } elseif ($this->class_location) {
            $this->class_type = 'physical';
        } else {
            $this->sendNotification('Error', 'Either virtual or physical class', 'error');
            return;
        }

        $this->class->class_location = $this->class_location;

        // Update schedule
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

        // Check if the schedule already exists, excluding the current schedule ID
        $scheduleExists = Schedule::where('id', '!=', $this->class->schedule->id)
                                ->whereHas('recurring_schedule', function ($query) {
                                    $query->whereIn('dates', $this->generatedDates);
                                })
                                ->whereTime('start_time', '<=', $this->end_time)
                                ->whereTime('end_time', '>', $this->start_time)
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
        } else {
            // notify the user that the schedule already exists
            $this->notification([
                'title'       => 'Schedule must be unique',
                'description' => 'You have chosen this schedule from one of your classes',
                'icon'        => 'error',
                'timeout'     => 2500,
            ]);

            return;
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
        <form wire:submit='editClass'>
            <div class="inline-flex mb-4 justify-between items-center w-full">
                <p class="capitalize font-semibold text-xl">Edit {{ $this->class_name }}</p>

                <div class="inline-flex gap-2 items-center">
                    <x-primary-button type='submit' wireTarget='editClass'>
                        Update Class
                    </x-primary-button>
                </div>
            </div>
            <div class="lg:grid lg:grid-cols-2 items-start gap-5 lg:space-y-0 space-y-3">

                {{-- general info --}}
                <div class="space-y-3">
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 gap-4">
                            <x-wui-datetime-picker
                                label="Start Date Time"
                                placeholder="January 1, 2000"
                                wire:model.blur="sched_initial_date"
                                parse-format="YYYY-MM-DD HH:mm"
                                display-format='dddd, MMMM D, YYYY'
                                :min="now()"
                                without-time
                                shadowless
                                errorless
                            />
                            <div class="inline-flex gap-4">
                                <x-wui-time-picker
                                    wire:model.blur='start_time'
                                    label="Start Time"
                                    placeholder="12:00 AM"
                                    interval="30"
                                    shadowless
                                    errorless
                                />
                                <x-wui-time-picker
                                    wire:model.blur='end_time'
                                    label="End Time"
                                    placeholder="12:00 PM"
                                    interval="30"
                                    shadowless
                                    errorless
                                />
                            </div>
                            <div class="flex flex-col gap-4" x-data="{ tab: window.location.hash ? window.location.hash : 'once' }">
                                {{-- Left panel as radio buttons --}}
                                <ul class="flex bg-[#F1F5F9] px-1.5 py-1.5 gap-2 rounded-lg">
                                    <li class="w-full text-center">
                                        <label :class="tab === 'once' ? 'bg-white' : ''"
                                            class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out">
                                            <input type="radio" wire:model.live='frequency' name="tab" value="once" x-model="tab" class="hidden">
                                            Do once
                                        </label>
                                    </li>
                                    <li class="w-full text-center">
                                        <label :class="tab === 'every' ? 'bg-white' : ''"
                                            class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out">
                                            <input type="radio" wire:model.live='frequency' name="tab" value="every" x-model="tab" class="hidden">
                                            Do every
                                        </label>
                                    </li>
                                </ul>

                                {{-- Right panel --}}
                                <div>
                                    <div x-show="tab == 'every'" class="space-y-2 mb-3" x-cloak>
                                        <div class="gap-2 inline-flex w-full">
                                            <x-wui-inputs.maskable
                                                wire:model.live='interval'
                                                label="Interval"
                                                placeholder='Interval'
                                                mask="##"
                                                shadowless
                                                errorless
                                            />
                                            <x-wui-select
                                                wire:model.live='interval_unit'
                                                label="Interval Unit"
                                                placeholder="Weeks"
                                                wire:model.defer="model"
                                                shadowless
                                                errorless
                                            >
                                                <x-wui-select.option label="Day/s" value="days" />
                                                <x-wui-select.option label="Week/s" value="weeks" />
                                                <x-wui-select.option label="Month/s" value="months" />
                                            </x-wui-select>
                                        </div>
                                        <div class="gap-2 inline-flex items-center w-full text-nowrap">
                                            <x-wui-inputs.maskable
                                                wire:model.live='occurrences'
                                                mask="##"
                                                label='End After'
                                                placeholder='occurrences'
                                                shadowless
                                                errorless
                                            />
                                        </div>
                                        @if ($interval && $interval_unit && $occurrences && $occurrences > $interval)
                                            <x-alert-blue title="{{ $occurrences }} scheduled dates will occur every {{ $interval }} {{ $interval_unit }}.">
                                                <div class="gap-2 space-y-2">
                                                    @foreach ($generatedDates as $date)
                                                        <x-wui-badge flat info label="{{ Carbon::create($date)->format('l, F j, Y') }}" />
                                                    @endforeach
                                                </div>
                                            </x-alert-blue>
                                        @endif
                                    </div>
                                    <x-wui-errors only='sched_initial_date|start_time|end_time|interval|interval_units|occurrences'/>
                                </div>
                            </div>
                        </div>

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
                    </div>
                </div>

                {{-- sched and regi --}}
                <div class="space-y-3">
                    {{-- class registration --}}
                    @if ($this->class_category == 'group')
                        <x-wui-datetime-picker
                            label="Registration Start"
                            placeholder="January 1, 2000"
                            wire:model.live="regi_start_date"
                            parse-format="YYYY-MM-DD HH:mm"
                            display-format='dddd, MMMM D, YYYY h:mm A'
                            :min="now()"
                            shadowless
                        />
                        <x-wui-datetime-picker
                            label="Registration End"
                            placeholder="December 1, 2000"
                            wire:model.blur="regi_end_date"
                            parse-format="YYYY-MM-DD HH:mm"
                            display-format='dddd, MMMM D, YYYY h:mm A'
                            :min="now()"
                            shadowless
                            />
                    @endif

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
                            <x-wui-inputs.currency wire:model='class_fee' label='Class Fee' icon="cash" placeholder="Enter class price" shadowless/>
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

    <x-wui-notifications position="bottom-right" />
</section>



