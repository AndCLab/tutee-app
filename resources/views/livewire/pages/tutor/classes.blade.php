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
use App\Models\Registration;
use App\Models\Fields;

new #[Layout('layouts.app')] class extends Component {
    use Actions;

    // class properties
    public string $class_name = '';
    public string $class_description = '';
    public string $class_type = '';
    public string $class_location = '';
    public string $class_link = '';
    public $class_fee = 0;
    public $class_status;
    public $class_fields = []; //fields setter
    public $getFields = []; // fields getter

    // for class schedule date
    public $sched_start_date;
    public $sched_end_date;

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
        $this->getFields = Fields::where('user_id', Auth::id())->get(['field_name'])->toArray();
        $this->fields = [''];
    }

    public function updatedSchedEndDate()
    {
        if ($this->sched_start_date) {
            $this->validateOnly(
                'sched_end_date', ['sched_end_date' => ['after:sched_start_date']]
            );
        }
    }

    // individual class validation and creation
    public function IndividualValidation()
    {
        $this->validate([
            'class_name' => ['required', 'string', 'max:255'],
            'class_description' => ['required', 'string', 'max:255'],
            'class_fields' => ['required'],
            'sched_start_date' => ['required', 'date'],
            'sched_end_date' => ['required', 'date', 'after:sched_start_date'],
            'class_location' => ['string', 'max:255'],
            'class_link' => ['string', 'max:255'],
        ]);
    }

    public function createIndividualClass()
    {
        $tutor = Tutor::where('user_id', Auth::id())->first();

        $this->IndividualValidation();

        if ($this->class_location) {
            $this->class_type = 'physical';
        } else if ($this->class_link) {
            $this->class_type = 'virtual';
            $this->class_location = $this->class_link;
        } else{
            $this->notification([
                'title'       => 'Error',
                'description' => 'Either virtual or physical class',
                'icon'        => 'error',
                'timeout'     => 2500,
            ]);

            return;
        }

        $schedule = Schedule::create([
            'start_date' => $this->sched_start_date,
            'end_date' => $this->sched_end_date
        ]);

        $classFieldsJson = is_array($this->class_fields) ? json_encode($this->class_fields) : $this->class_fields;

        Classes::create([
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

        $this->reset(
            'class_name',
            'class_description',
            'class_fields',
            'sched_start_date',
            'sched_end_date',
            'class_location',
            'class_fee',
            'class_link',
        );

        $this->notification([
            'title'       => 'Fresh Class!',
            'description' => 'Successfully created!',
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);

        $this->dispatch('new-class', isNotEmpty: 0);
    }

    // group class validation and creation
    public function GroupValidation()
    {
        $this->validate([
            'class_name' => ['required', 'string', 'max:255'],
            'class_description' => ['required', 'string', 'max:255'],
            'class_fields' => ['required'],

            'regi_start_date' => ['required', 'date'],
            'regi_end_date' => ['required', 'date', 'after:regi_start_date'],

            'sched_start_date' => ['required', 'date', 'after:regi_start_date', 'after:regi_end_date'],
            'sched_end_date' => ['required', 'date', 'after:regi_start_date', 'after:regi_end_date' ,'after:sched_start_date'],

            'class_location' => ['string', 'max:255'],
            'class_link' => ['string', 'max:255'],
        ]);
    }

    public function createGroupClass()
    {
        $tutor = Tutor::where('user_id', Auth::id())->first();

        $this->GroupValidation();

        if ($this->class_location) {
            $this->class_type = 'physical';
        } else if ($this->class_link) {
            $this->class_type = 'virtual';
            $this->class_location = $this->class_link;
        } else{
            $this->notification([
                'title'       => 'Error',
                'description' => 'Either virtual or physical class',
                'icon'        => 'error',
                'timeout'     => 2500,
            ]);

            return;
        }

        $schedule = Schedule::create([
            'start_date' => $this->sched_start_date,
            'end_date' => $this->sched_end_date
        ]);

        $registration = Registration::create([
            'start_date' => $this->regi_start_date,
            'end_date' => $this->regi_end_date
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
            'class_fee' => $this->class_fee,
            'class_status' => 1,
            'schedule_id' => $schedule->id,
            'registration_id' => $registration->id
        ]);

        $this->reset(
            'class_name',
            'class_description',
            'class_fields',
            'sched_start_date',
            'sched_end_date',
            'class_location',
            'class_fee',
            'class_link',
        );

        $this->notification([
            'title'       => 'Fresh Class!',
            'description' => 'Successfully created!',
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);

        $this->dispatch('new-class', isNotEmpty: 0);
    }

}; ?>

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

    {{-- class schedule modal --}}
    <x-wui-modal wire:model="showClassSchedule" max-width='md' persistent>
        <x-wui-card title='Class Schedule'>
            <div class="grid grid-cols-1 gap-4">
                <x-wui-datetime-picker
                    label="Start Date Time"
                    placeholder="January 1, 2000"
                    wire:model.blur="sched_start_date"
                    parse-format="YYYY-MM-DD HH:mm"
                    display-format='dddd, MMMM D, YYYY h:mm A'
                    :min="now()"
                    shadowless
                />
                <x-wui-datetime-picker
                    label="End Date Time"
                    placeholder="December 1, 2000"
                    wire:model.live="sched_end_date"
                    parse-format="YYYY-MM-DD HH:mm"
                    display-format='dddd, MMMM D, YYYY h:mm A'
                    :min="now()"
                    shadowless
                />
            </div>
            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-wui-button primary label="Done" spinner='showClassSchedule' x-on:click='close' />
                </div>
            </x-slot>
        </x-wui-card>
    </x-wui-modal>

    {{-- class registration modal --}}
    <x-wui-modal wire:model="showRegistrationDate" max-width='md' persistent>
        <x-wui-card title='Class Registration Date'>
            <div class="grid grid-cols-1 gap-4">
                <x-wui-datetime-picker
                    label="Start Date Time"
                    placeholder="January 1, 2000"
                    wire:model.blur="regi_start_date"
                    parse-format="YYYY-MM-DD HH:mm"
                    display-format='dddd, MMMM D, YYYY h:mm A'
                    :min="now()"
                    shadowless
                />
                <x-wui-datetime-picker
                    label="End Date Time"
                    placeholder="December 1, 2000"
                    wire:model.blur="regi_end_date"
                    parse-format="YYYY-MM-DD HH:mm"
                    display-format='dddd, MMMM D, YYYY h:mm A'
                    :min="now()"
                    shadowless
                />
            </div>
            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-wui-button primary label="Done" spinner='showClassSchedule' x-on:click='close' />
                </div>
            </x-slot>
        </x-wui-card>
    </x-wui-modal>

    <x-wui-notifications position="bottom-right" />
</section>
