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

    // states
    #[Url(as: 'search_class')]
    public $search = '';
    public $QueryNotFound;
    public bool $isEmptyClass;
    public $sort_by;

    public function mount()
    {
        // default value: descending
        $this->sort_by = '2';

        $this->allClasses = Classes::orderBy('created_at', 'desc')->get();

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
        $this->allClasses = Classes::orderBy('created_at', 'desc')->get();
    }

    public function updatedSortBy()
    {
        if ($this->sort_by == '1') {
            $this->allClasses = Classes::orderBy('created_at', 'asc')->get();
        } elseif ($this->sort_by == '2') {
            $this->allClasses = Classes::orderBy('created_at', 'desc')->get();
        }
    }

    public function updatedSearch()
    {
        if (!$this->isEmptyClass) {
            $this->allClasses = Classes::where('class_name', 'like', '%' . $this->search . '%')->get();

            if ($this->allClasses->isEmpty()) {
                $this->QueryNotFound = 'No results found for your search.';
            } else {
                $this->QueryNotFound = '';
            }
        }
    }

}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <p class="capitalize font-semibold text-xl mb-9">class list</p>

    {{-- Class List: Search and Filter --}}
    <div class="flex gap-2 pb-3">
        <div class="w-full">
            <x-wui-input wire:model.live.='search' placeholder='Search class...' icon='search' shadowless />
        </div>
        <div class="w-fit">
            <x-wui-select wire:model.live='sort_by' placeholder="Sort by" shadowless>
                <x-wui-select.option label="Ascending" value="1" />
                <x-wui-select.option label="Descending" value="2" />
            </x-wui-select>
        </div>
        <x-wui-dropdown>
            <x-slot name="trigger">
                <x-wui-button.circle flat md squared icon='adjustments' />
            </x-slot>

            <x-wui-dropdown.item label="View all classes" />
            <x-wui-dropdown.item label="View pending classes" />
        </x-wui-dropdown>
    </div>

    {{-- Class List: Class Cards --}}
    <div class="space-y-3">
        @if (!$isEmptyClass)
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
                            <p>Create at {{ Carbon::create($class['created_at'])->format('l jS \\of F Y h:i A') }}</p>
                        </div>
                    </div>
                    <p class="line-clamp-3 antialiased leading-snug">
                        {{ $class['class_description'] }}
                    </p>
                </div>
            @endforeach
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

    {{-- skeleton for search --}}
    <div class="w-full" wire:loading wire:target='search'>
        <div class="animate-pulse w-full space-y-4 rounded-md bg-[#F1F5F9] p-4">
            <div class="space-y-1">
            <div class="flex items-center justify-between">
                <div class="h-6 w-60 bg-[#D4E0EC] rounded-md"></div>
                <div class="h-6 w-2 bg-[#D4E0EC] rounded-md"></div>
            </div>
            <div class="flex items-center gap-2">
                <div class="h-4 w-6 bg-[#D4E0EC] rounded-md"></div>
                <p></p>
            </div>
            </div>
            <div class="flex flex-col gap-1">
            <div class="h-3 w-full bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-5/6 bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-3/4 bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-2/4 bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-1/4 bg-[#D4E0EC] rounded-md"></div>
            </div>
        </div>
    </div>

    {{-- skeleton for sortby --}}
    <div class="w-full" wire:loading wire:target='sort_by'>
        <div class="animate-pulse w-full space-y-4 rounded-md bg-[#F1F5F9] p-4">
            <div class="space-y-1">
            <div class="flex items-center justify-between">
                <div class="h-6 w-60 bg-[#D4E0EC] rounded-md"></div>
                <div class="h-6 w-2 bg-[#D4E0EC] rounded-md"></div>
            </div>
            <div class="flex items-center gap-2">
                <div class="h-4 w-6 bg-[#D4E0EC] rounded-md"></div>
                <p></p>
            </div>
            </div>
            <div class="flex flex-col gap-1">
            <div class="h-3 w-full bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-5/6 bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-3/4 bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-2/4 bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-1/4 bg-[#D4E0EC] rounded-md"></div>
            </div>
        </div>
    </div>

</section>
