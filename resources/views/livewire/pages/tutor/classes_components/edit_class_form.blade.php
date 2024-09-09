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

new #[Layout('layouts.app')] class extends Component {

    // get auth tutor
    public $tutor;

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
        $class = Classes::findOrFail($id);
        $schedules = RecurringSchedule::where('schedule_id', $class->schedule->id)->get();

        $this->getFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])
                                ->toArray();

        $this->class_name = $class->class_name;
        $this->class_description = $class->class_description;
        $this->class_type = $class->class_type;
        $this->class_category = $class->class_category;

        if ($class->class_students) {
            $this->class_students = $class->class_students;
        }

        if ($class->class_type == 'virtual') {
            $this->class_link = $class->class_location;
        } elseif ($class->class_type == 'physical') {
            $this->class_location = $class->class_location;
        }

        $this->class_fee = $class->class_fee;
        $this->class_fields = json_decode($class->class_fields, true); //fields setter

        // for class schedule date

        // for registration date
        if ($class->class_category == 'group' && $class->registration !== null) {
            $this->regi_start_date = $class->registration->start_date;
            $this->regi_end_date = $class->registration->end_date;
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

}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">
        <form wire:submit='editClass'>
            <div class="inline-flex mb-4 justify-between items-center w-full">
                <p class="capitalize font-semibold text-xl">Edit {{ $class_name }}</p>

                <div class="inline-flex gap-2 items-center">
                    <x-primary-button type='submit' wireTarget='editClass'>
                        Update Class
                    </x-primary-button>
                </div>
            </div>
            <div class="lg:grid lg:grid-cols-2 items-start gap-5">

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
                            <div class="inline-flex gap-2">
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
                                                :disabled="$start_time == null && $end_time == null"
                                            />
                                            <x-wui-select
                                                wire:model.live='interval_unit'
                                                label="Interval Unit"
                                                placeholder="Weeks"
                                                wire:model.defer="model"
                                                shadowless
                                                errorless
                                                :disabled="$interval == null"
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
                                                :disabled="$interval == null && $interval_unit == null"
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

                        {{-- class name --}}
                        <div class="{{ $class_category == "group" ? 'grid grid-cols-2 gap-4' : '' }}">
                            <x-wui-input wire:model='class_name' label="Class Name" placeholder='Enter class name' shadowless/>
                            {{-- class students --}}
                            @if ($class_category == "group")
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
                    @if ($class_category == 'group')
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
                                {{-- @foreach ($class_fields as $field)
                                    <x-wui-select.option
                                        label="{{ $field }}"
                                        value="{{ $field }}"
                                    />
                                @endforeach --}}
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
                </div>
            </div>
        </form>
    </div>
</section>



