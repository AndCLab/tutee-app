<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use WireUi\Traits\Actions;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Carbon\Carbon;
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
    public string $class_category = '';
    public string $class_link = '';
    public $class_fee = 0;
    public $class_fields = []; //fields setter
    public $getFields = []; // fields getter

    // for class schedule date
    public $sched_start_date;
    public $sched_end_date;

    // for registration date
    public $regi_start_date;
    public $regi_end_date;

    public $allClasses;
    public $pendingClasses;

    public $classFilter;

    // states
    #[Url(as: 'search_class')]
    public $searchAll = '';

    #[Url(as: 'search_pending')]
    public $searchPending = '';

    public $QueryNotFound;
    public bool $isEmptyAll = false;
    public bool $isEmptyPending = false;
    public $sort_by;

    public $showWithdrawClassModal;
    public $showEditClassModal;
    public $editClassId;

    public function mount()
    {
        // default value: descending
        $this->sort_by = 'desc';

        $this->allClasses = Classes::orderBy('created_at', $this->sort_by)
                                    ->get();
        $this->pendingClasses = Classes::where('class_status', 1)
                                    ->orderBy('created_at', $this->sort_by)
                                    ->get();

        $this->getFields = Fields::where('user_id', Auth::id())->get(['field_name'])->toArray();

        // default
        $this->classFilter = 'pending';

        $this->isEmptyAll = $this->allClasses->isEmpty();
        $this->isEmptyPending = $this->pendingClasses->isEmpty();
    }

    #[On('new-class')]
    public function updateList($isNotEmpty)
    {
        $this->isEmptyPending = $isNotEmpty;
        $this->isEmptyAll = $isNotEmpty;

        $this->allClasses = Classes::orderBy('created_at', $this->sort_by)
                                    ->get();
        $this->pendingClasses = Classes::where('class_status', 1)
                                    ->orderBy('created_at', $this->sort_by)
                                    ->get();
    }

    public function updatedSortBy()
    {
        $sortOrder = in_array($this->sort_by, ['asc', 'desc']) ? $this->sort_by : 'asc';

        if ($this->classFilter === 'all') {
            $this->allClasses = Classes::orderBy('created_at', $sortOrder)
                                        ->get();
        } elseif ($this->classFilter === 'pending') {
            $this->pendingClasses = Classes::where('class_status', 1)
                                        ->orderBy('created_at', $sortOrder)
                                        ->get();
        }

    }

    public function viewAll()
    {
        $this->classFilter = 'all';
        $this->allClasses = Classes::orderBy('created_at', $this->sort_by)
        ->get();
    }

    public function viewPending()
    {
        $this->classFilter = 'pending';
        $this->pendingClasses = Classes::where('class_status', 1)
                                        ->orderBy('created_at', $this->sort_by)
                                        ->get();
    }

    public function updatedSearchAll()
    {
        $this->searchClasses('all', $this->searchAll);
    }

    public function updatedSearchPending()
    {
        $this->searchClasses('pending', $this->searchPending);
    }

    protected function searchClasses(string $filter, string $searchTerm)
    {
        if (($filter === 'all' && !$this->isEmptyAll) || ($filter === 'pending' && !$this->isEmptyPending)) {
            $query = Classes::orderBy('created_at', $this->sort_by)
                            ->where('class_name', 'like', '%' . $searchTerm . '%');

            if ($filter === 'pending') {
                $query->where('class_status', 1);
            }

            $result = $query->get();

            if ($filter === 'all') {
                $this->allClasses = $result;
            } elseif ($filter === 'pending') {
                $this->pendingClasses = $result;
            }

            $this->QueryNotFound = $result->isEmpty() ? 'No results found for your search.' : '';
        }
    }

    // action buttons
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

    public function editClass()
    {
        $edit = Classes::find($this->editClassId);

        $this->IndividualValidation();

        $edit->class_name = $this->class_name;
        $edit->class_description = $this->class_description;
        $edit->class_fields = is_array($this->class_fields) ? json_encode($this->class_fields) : $this->class_fields;

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

        if ($edit->schedule) {
            $edit->schedule->start_date = $this->sched_start_date;
            $edit->schedule->end_date = $this->sched_end_date;
            $edit->schedule->save();
        }

        if ($edit->class_category == 'group' && $edit->registration) {
            $edit->registration->start_date = $this->regi_start_date;
            $edit->registration->end_date = $this->regi_end_date;
            $edit->registration->save();
        }

        $edit->class_fee = $this->class_fee;

        $edit->save();

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
            'description' => 'Successfully updated!',
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);

        $this->showEditClassModal = false;

        $this->dispatch('new-class', isNotEmpty: 0);
    }

    public function resetModalState()
    {
        $this->reset([
            'showEditClassModal', 'editClassId', 'class_name', 'class_description',
            'class_type', 'class_category', 'class_link', 'class_location',
            'class_fee', 'class_fields', 'sched_start_date', 'sched_end_date',
            'regi_start_date', 'regi_end_date'
        ]);
    }

    public function editClassModal($editClass)
    {
        $this->showEditClassModal = true;
        $this->editClassId = $editClass;

        $edit = Classes::find($editClass);

        $this->class_name = $edit->class_name;
        $this->class_description = $edit->class_description;
        $this->class_type = $edit->class_type;
        $this->class_category = $edit->class_category;

        if ($edit->class_type == 'virtual') {
            $this->class_link = $edit->class_location;
        } elseif ($edit->class_type == 'physical') {
            $this->class_location = $edit->class_location;
        }

        $this->class_fee = $edit->class_fee;
        $this->class_fields = json_decode($edit->class_fields, true); //fields setter

        // for class schedule date
        if ($edit->schedule) {
            $this->sched_start_date = $edit->schedule->start_date;
            $this->sched_end_date = $edit->schedule->end_date;
        }

        // for registration date
        if ($edit->class_category == 'group' && $edit->registration !== null) {
            $this->regi_start_date = $edit->registration->start_date;
            $this->regi_end_date = $edit->registration->end_date;
        }

    }

    // delete class
    public function withdrawClass()
    {
        $class = Classes::find($this->editClassId);
        $sched = $class->schedule;
        $regi = $class->registration;

        if ($sched) {
            $sched->delete();
        }

        if ($regi) {
            $regi->delete();
        }

        $this->notification([
            'title'       => 'Removed',
            'description' => 'Successfully remove class',
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);

        $this->showWithdrawClassModal = false;
        $this->mount();
    }

    public function withdrawClassModal($classId)
    {
        $this->showWithdrawClassModal = true;
        $this->editClassId = $classId;
    }

}; ?>

@php
    $classes = ($classFilter == 'all') ? $allClasses : $pendingClasses;
@endphp

<section>
    <x-slot name="header">
    </x-slot>

    <p class="capitalize font-semibold text-xl mb-9">class list</p>

    {{-- Class List: Search and Filter --}}
    <div class="flex gap-2 pb-3">
        @if ($classFilter == 'all')
            <div class="w-full">
                <x-wui-input wire:model.live='searchAll' placeholder='Search class...' icon='search' shadowless />
            </div>
        @else
            <div class="w-full">
                <x-wui-input wire:model.live='searchPending' placeholder='Search pending class...' icon='search' shadowless />
            </div>
        @endif
        <div class="w-fit">
            <x-wui-select wire:model.live='sort_by' placeholder="Sort by" shadowless>
                <x-wui-select.option label="Ascending" value="asc" />
                <x-wui-select.option label="Descending" value="desc" />
            </x-wui-select>
        </div>
        <x-wui-dropdown>
            <x-slot name="trigger">
                <x-wui-button.circle flat md squared icon='adjustments' />
            </x-slot>

            <x-wui-dropdown.item wire:click='viewAll' spinner='viewAll' label="View all classes" />
            <x-wui-dropdown.item wire:click='viewPending' spinner='viewPending' label="View pending classes" />
        </x-wui-dropdown>
    </div>

    {{-- Class List: Class Cards --}}
    <div class="space-y-3">
        @if (!$isEmptyAll || !$isEmptyPending)
            @foreach ($classes as $class)
                <div class="w-full bg-[#F1F5F9] p-4 pb-2 rounded-md text-[#0F172A] space-y-4" wire:loading.remove>
                    <div class="space-y-1">
                        <div class="flex justify-between items-center">
                            <div class="inline-flex items-center gap-2">
                                <p class="font-semibold">{{ $class->class_name }}</p>
                                @if ($class->class_category == 'group')
                                    <x-wui-badge flat warning label="{{ $class->class_category }}" />
                                @else
                                    <x-wui-badge flat purple label="{{ $class->class_category }}" />
                                @endif
                            </div>
                            <x-wui-dropdown>
                                <x-wui-dropdown.header class="font-semibold" label="Actions">
                                    @if ($class->class_status == 1)
                                        <x-wui-dropdown.item wire:click='editClassModal({{ $class->id }})' icon='pencil-alt' label="Edit" />
                                        <x-wui-dropdown.item wire:click='withdrawClassModal({{ $class->id }})' icon='trash' label="Withdraw" />
                                    @endif
                                </x-wui-dropdown.header>
                            </x-wui-dropdown>
                        </div>
                        <div class="flex gap-2 items-center text-[#64748B] text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p>Created at {{ Carbon::create($class['created_at'])->format('l jS \\of F Y h:i A') }}</p>
                        </div>
                    </div>
                    <p class="line-clamp-3 antialiased leading-snug">
                        {{ $class->class_description }}
                    </p>
                    <div x-data="{ expanded: false }">
                        <div class="text-sm" x-show="expanded" x-collapse x-cloak>
                            <p>
                                Class Status:
                                @if ($class->class_status == 1)
                                    Open
                                @else
                                    Closed
                                @endif
                            </p>
                            <p>
                                Class Type: {{ ucfirst($class->class_type) }}
                            </p>
                            <p>
                                Class Fee:
                                @if ($class->class_fee == 0.00)
                                    Free Class
                                @else
                                    {{ number_format($class->class_fee, 2) }}
                                @endif
                            </p>
                            <p>
                                Class Fields:
                                @foreach ($fields = json_decode($class->class_fields, true) as $index => $item)
                                    @if ($index < count($fields) - 1)
                                        {{ $item . ',' }}
                                    @else
                                        {{ $item }}
                                    @endif
                                @endforeach
                            </p>
                            <p>
                                Class Location: {{ $class->class_location }}
                            </p>
                            <p>
                                Starts at {{ Carbon::create($class->schedule->start_date)->format('l jS \\of F Y h:i A') }}
                            </p>
                        </div>

                        <div class="flex justify-end">
                            <template x-if='expanded == false' x-transition>
                                <x-wui-button @click="expanded = ! expanded" xs label='View more' icon='arrow-down' flat />
                            </template>
                            <template x-if='expanded == true' x-transition>
                                <x-wui-button @click="expanded = ! expanded" xs label='View more' icon='arrow-up' flat />
                            </template>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        @if ($classFilter == 'pending' && $isEmptyPending)
            <div class="flex flex-col gap-3 justify-center items-center w-full" wire:loading.remove>
                <img class="size-60" src="{{ asset('images/empty_class.svg') }}" alt="">
                <p class="font-semibold text-xl">You don't have any pending classes</p>
            </div>
        @elseif($classFilter == 'all' && $isEmptyAll)
            <div class="flex flex-col gap-3 justify-center items-center w-full" wire:loading.remove>
                <img class="size-60" src="{{ asset('images/empty_class.svg') }}" alt="">
                <p class="font-semibold text-xl">Start by creating your own class</p>
            </div>
        @endif


        @if ($QueryNotFound)
            <h1 wire:loading.class='hidden'>
                {{ $QueryNotFound }}
            </h1>
        @endif
    </div>

    <x-wui-modal wire:model="showEditClassModal" persistent>
        <x-wui-card title="Edit Class">
            @include('livewire.pages.tutor.classes_components.edit_class')
        </x-wui-card>
    </x-wui-modal>

    <x-wui-modal wire:model="showWithdrawClassModal" persistent align='center' max-width='sm'>
        <x-wui-card title="Delete Class">
            <p class="text-gray-600">
                Do you wanna remove this class?
                <span class="font-semibold">
                    {{
                        Classes::where('id', $editClassId)->pluck('class_name')->first();
                    }}
                </span>
            </p>

            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-wui-button flat label="Cancel" x-on:click="close" />
                    <x-wui-button wire:click='withdrawClass' spinner='withdrawClass' negative label="Yes, Delete it" />
                </div>
            </x-slot>
        </x-wui-card>
    </x-wui-modal>

    <x-class-skeleton loadClass='searchAll' />
    <x-class-skeleton loadClass='searchPending' />
    <x-class-skeleton loadClass='sort_by' />
    <x-class-skeleton loadClass='viewAll' />
    <x-class-skeleton loadClass='viewPending' />
    <x-class-skeleton loadClass='withdrawClass' />
    <x-class-skeleton loadClass='withdrawClassModal' />
    <x-class-skeleton loadClass='editClass' />
    <x-class-skeleton loadClass='editClassModal' />
    <x-class-skeleton loadClass='resetModalState' />

</section>
