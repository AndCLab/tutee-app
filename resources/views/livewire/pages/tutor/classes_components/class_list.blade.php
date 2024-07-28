<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
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
    // class properties
    public $allClasses;
    public $pendingClasses;

    public $classFilter;

    // states
    #[Url(as: 'search_class')]
    public $searchAll = '';

    #[Url(as: 'search_pending')]
    public $searchPending = '';
    public $QueryNotFound;
    public bool $isEmptyClass;
    public $sort_by;

    public function mount()
    {
        // default value: descending
        $this->sort_by = 'desc';

        $this->allClasses = Classes::where('class_status', 1)
                                    ->orderBy('created_at', $this->sort_by)
                                    ->get();
        $this->pendingClasses = Classes::where('class_status', 1)
                                    ->orderBy('created_at', $this->sort_by)
                                    ->get();

        if ($this->allClasses->isEmpty()) {
            $this->isEmptyClass = 1;
        } else{
            $this->isEmptyClass = 0;
        }
    }

    #[On('new-class')]
    public function updateList($isNotEmpty)
    {
        $this->isEmptyClass = $isNotEmpty;
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
        if (!$this->isEmptyClass) {
            $this->allClasses = Classes::orderBy('created_at', $this->sort_by)
                                        ->where('class_name', 'like', '%' . $this->searchAll . '%')
                                        ->get();

            $this->QueryNotFound = $this->allClasses->isEmpty()
                ? 'No results found for your search.'
                : '';
        }
    }

    public function updatedSearchPending()
    {
        if (!$this->isEmptyClass) {
            $this->pendingClasses = Classes::where('class_status', 1)
                                            ->orderBy('created_at', $this->sort_by)
                                            ->where('class_name', 'like', '%' . $this->searchPending . '%')
                                            ->get();

            $this->QueryNotFound = $this->pendingClasses->isEmpty()
                ? 'No results found for your search.'
                : '';
        }
    }

}; ?>

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
        @if (!$isEmptyClass)
            @if ($classFilter == 'all')
                @foreach ($allClasses as $class)
                    <div class="w-full bg-[#F1F5F9] p-4 rounded-md text-[#0F172A] space-y-4" wire:loading.remove>
                        <div class="space-y-1">
                            <div class="flex justify-between items-center">
                                <p class="font-semibold">{{ $class['class_name'] }}</p>
                                <x-wui-dropdown>
                                    <x-wui-dropdown.header class="font-semibold" label="Actions">
                                        <x-wui-dropdown.item icon='eye' label="Inspect" />
                                        <x-wui-dropdown.item icon='pencil-alt' label="Edit" />
                                        <x-wui-dropdown.item icon='trash' label="Withdraw" />
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
                            {{ $class['class_description'] }}
                        </p>
                    </div>
                @endforeach
            @else
                @foreach ($pendingClasses as $class)
                    <div class="w-full bg-[#F1F5F9] p-4 rounded-md text-[#0F172A] space-y-4" wire:loading.remove>
                        <div class="space-y-1">
                            <div class="flex justify-between items-center">
                                <p class="font-semibold">{{ $class['class_name'] }}</p>
                                <x-wui-dropdown>
                                    <x-wui-dropdown.header class="font-semibold" label="Actions">
                                        <x-wui-dropdown.item icon='eye' label="Inspect" />
                                        <x-wui-dropdown.item icon='pencil-alt' label="Edit" />
                                        <x-wui-dropdown.item icon='trash' label="Withdraw" />
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
                            {{ $class['class_description'] }}
                        </p>
                    </div>
                @endforeach
            @endif
        @else
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

    <x-class-skeleton loadClass='searchAll' />
    <x-class-skeleton loadClass='searchPending' />
    <x-class-skeleton loadClass='sort_by' />
    <x-class-skeleton loadClass='viewAll' />
    <x-class-skeleton loadClass='viewPending' />

</section>
