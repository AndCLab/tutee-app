<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Carbon\Carbon;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Classes;
use App\Models\Fields;

new #[Layout('layouts.app')] class extends Component {
    public $title = 'Tutors | Tutee';

    public $tutors;
    public $pages = 5;

    public $selectedTutor;
    public $selectedClass;
    public $ST_INDIClasses;
    public $ST_GRClasses;

    // states
    #[Url(as: 'search_tutor')]
    public $search;

    #[Url(as: 'show_tutor')]
    public $show_tutor;

    #[Url(as: 'sort_by')]
    public $sort_by = 'asc';

    #[Url(as: 'time_avail')]
    public $time_avail;

    #[Url(as: 'pricing')]
    public $pricing;

    #[Url(as: 'class_fields')]
    public $class_fields = [];

    #[Url(as: 'tutor_type')]
    public $tutor_type;

    public $getFields = []; // fields getter

    public function mount()
    {
        $this->getFields = Fields::where('user_id', Auth::id())
            ->get(['field_name'])
            ->toArray();
        $this->tutors = Tutor::take($this->pages)->get();
        $this->selectedTutor = null;
    }

    public function loadMore()
    {
        $this->pages += 5;
        if (!$this->updated()) {
            $this->tutors = Tutor::take($this->pages)->get();
        }
    }

    public function selectTutor($tutor_id)
    {
        $this->selectedTutor = Tutor::findOrFail($tutor_id);
        $this->show_tutor = $this->selectedTutor->user->fname;

        $this->ST_INDIClasses = Classes::where('tutor_id', $tutor_id)->where('class_category', 'individual')->count();
        $this->ST_GRClasses = Classes::where('tutor_id', $tutor_id)->where('class_category', 'group')->count();

        $this->selectedClass = Classes::where('tutor_id', $tutor_id)->get();
    }

    public function updated()
    {
        $this->tutors = Tutor::when($this->search, function ($q) {
                                $q->whereHas('user', function ($query) {
                                    $query->where('fname', 'like', "%{$this->search}%")
                                        ->orWhere('lname', 'like', "%{$this->search}%");
                                });
                            })
                            ->when($this->sort_by, function ($q) {
                                $q->orderBy('created_at', $this->sort_by);
                            })
                            ->when($this->time_avail, function ($q) {
                                $q->whereHas('classes.schedule', function ($query) {
                                    $query->where('start_date', 'like', "%{$this->time_avail}%");
                                });
                            })
                            ->when($this->class_fields, function ($q) {
                                $q->whereHas('user.fields', function ($query) {
                                    $query->whereIn('field_name', $this->class_fields);
                                });
                            })
                            ->when($this->pricing, function ($q) {
                                $q->whereHas('classes', function ($query) {
                                    $query->where('class_fee', '>=', $this->pricing)
                                            ->orderBy('class_fee', $this->sort_by);
                                });
                            })
                            ->take($this->pages)
                            ->get();

        // $user = Auth::user();

        // $fields = Fields::where('user_id', $user->id)
        //                 ->whereHas('users', function($q) use ($user) {
        //                     $q->where('user_type', $user->user_type);
        //                 })
        //                 ->get();

        // dd($fields);

        // dd($this->tutors->pluck('user_id'));

        return true;
    }

}; ?>

@push('title')
    {{ $title }}
@endpush

@php
    if ($selectedTutor) {
        $tutor_name = $selectedTutor->user->fname . ' ' . $selectedTutor->user->lname;
    }
@endphp

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">

        {{-- filter container --}}
        <div class="lg:grid lg:grid-row items-start gap-5 pb-3">
            <p class="capitalize font-semibold text-xl">Find Tutors</p>

            {{-- Class List: Search and Filter --}}

            <div class="grid grid-cols-8 gap-2">
                <div class="space-y-2 col-span-5">
                    {{-- search --}}
                    <div class="w-full">
                        <x-wui-input wire:model.live='search' placeholder='Search tutor...' icon='search' shadowless />
                    </div>

                    <div class="inline-flex gap-2">
                        {{-- pricing --}}
                        <div class="w-fit">
                            <x-wui-inputs.currency wire:model.live.debounce.250ms='pricing' icon="cash" placeholder="Pricing" shadowless />
                        </div>

                        {{-- sort by --}}
                        <div class="w-fit">
                            <x-wui-select wire:model.live='sort_by' placeholder="Sort by" shadowless>
                                <x-wui-select.option label="Ascending" value="asc" />
                                <x-wui-select.option label="Descending" value="desc" />
                            </x-wui-select>
                        </div>

                        {{-- tutor type --}}
                        <div class="w-fit">
                            <x-wui-select wire:model.live='tutor_type' placeholder="Tutor Type" shadowless>
                                <x-wui-select.option label="Accredited" value="accre" />
                                <x-wui-select.option label="Non-Accredited" value="non_accre" />
                            </x-wui-select>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col w-full gap-2 pb-3 col-span-3">

                    {{-- class fields --}}
                    <div class="w-full">
                        <div>
                            <x-wui-select wire:model.live="class_fields" placeholder="Class Fields" multiselect shadowless>
                                @foreach ($getFields as $field)
                                    <x-wui-select.option label="{{ $field['field_name'] }}"
                                        value="{{ $field['field_name'] }}" />
                                @endforeach
                            </x-wui-select>
                        </div>
                    </div>

                    {{-- time avail --}}
                    <div class="w-full">
                        <x-wui-datetime-picker
                            placeholder="Availability"
                            wire:model.live="time_avail"
                            parse-format="YYYY-MM-DD HH:mm"
                            display-format='dddd, MMMM D, YYYY'
                            :min="now()"
                            without-time
                            shadowless
                        />
                    </div>
                </div>
            </div>

        </div>

        <section class="grid grid-cols-10 gap-2 items-start" x-data="{ active: null }" x-cloak>

            {{-- left panel --}}
            <div class="col-span-4 space-y-3">
                {{-- tutor cards --}}
                @forelse ($tutors as $tutor)
                    <div class="p-2 rounded cursor-pointer hover:bg-[#F2F2F2]"
                        {{-- :class="{ 'outline-1 outline outline-[#0C3B2E]': active === {{ $tutor->id }} }" --}}
                        :class="{ 'border-l-2 rounded-l-none border-[#0C3B2E]': active === {{ $tutor->id }} }"
                        wire:click="selectTutor({{ $tutor->id }})" x-on:click="active = {{ $tutor->id }}">

                        {{-- new tutor identifier --}}
                        <div class="mb-2">
                            @if (Carbon::create($tutor->created_at)->greaterThan(Carbon::now()->subWeek()))
                                <x-wui-badge flat indigo label="New Tutor" />
                            @endif
                        </div>

                        {{-- profile pic and name --}}
                        <div class="flex gap-3 items-start">
                            @if ($tutor->user->avatar !== null)
                                <img class="border-2 border-[#F1F5F9] overflow-hidden rounded-md size-14"
                                    src="{{ Storage::url($tutor->user->avatar) }}">
                            @else
                                <img class="border-2 border-[#F1F5F9] overflow-hidden rounded-md size-14"
                                    src="{{ asset('images/default.jpg') }}">
                            @endif
                            <div class="flex flex-col space-y-1">
                                <div class="inline-flex items-center gap-1">
                                    <p class="text-sm font-medium">{{ $tutor->user->fname . ' ' . $tutor->user->lname }}</p>
                                    <x-wui-icon name='badge-check' class="size-4 text-[#292D32]" solid />
                                </div>
                                <div class="inline-flex items-center gap-1">
                                    <x-wui-icon name='academic-cap' class="size-4 text-[#64748B]" solid />
                                    <span class="text-xs text-[#64748B]">BS Information Technology</span>
                                </div>
                            </div>
                        </div>

                        {{-- tutor class fields --}}
                        <div class="space-y-2">
                            @foreach ($fields = Fields::where('user_id', $tutor->user->id)->get() as $index => $item)
                                @break ($index === 3)
                                @if (in_array($item->field_name, $class_fields))
                                    <x-wui-badge flat rose label="{{ $item->field_name }}" />
                                @else
                                    <x-wui-badge flat slate label="{{ $item->field_name }}" />
                                @endif
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div>
                        Empty
                    </div>
                @endforelse

                {{-- loading state --}}
                <div x-intersect.full.threshold.50='$wire.loadMore()'>
                    <div wire:loading wire:target="loadMore" class="w-full flex flex-col bg-white rounded-xl">
                        <div class="flex flex-auto flex-col justify-center items-center">
                            <div class="flex justify-center">
                                <div class="animate-spin inline-block size-7 border-[3px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500" role="status" aria-label="loading">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- right panel --}}
            <div class="col-span-6 sticky top-[5rem] p-6 bg-white rounded border overflow-y-auto max-h-[85vh] soft-scrollbar">
                @if ($selectedTutor)
                    {{-- h-[77vh] --}}
                    <div wire:loading wire:target="selectTutor" class="w-full flex flex-col bg-white rounded-xl">
                        <div class="flex flex-auto flex-col justify-center items-center p-4 md:p-5">
                            <div class="flex justify-center">
                                <div class="animate-spin inline-block size-8 border-[3px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500" role="status" aria-label="loading">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div wire:loading.remove wire:target="selectTutor">

                        {{-- profile and bio --}}
                        <div>
                            <div class="flex gap-4">
                                @if ($selectedTutor->user->avatar !== null)
                                    <img class="rounded-md size-24" src="{{ Storage::url($selectedTutor->user->avatar) }}">
                                @else
                                    <img class="rounded-md size-24" src="{{ asset('images/default.jpg') }}">
                                @endif
                                <div class="flex justify-between w-full">
                                    <div class="space-y-2">
                                        <div class="flex gap-2 items-center">
                                            <h2 class="text-xl font-semibold">{{ $tutor_name }}</h2>
                                            <svg class="size-5" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M3 2.5C3 2.22386 3.22386 2 3.5 2H11.5C11.7761 2 12 2.22386 12 2.5V13.5C12 13.6818 11.9014 13.8492 11.7424 13.9373C11.5834 14.0254 11.3891 14.0203 11.235 13.924L7.5 11.5896L3.765 13.924C3.61087 14.0203 3.41659 14.0254 3.25762 13.9373C3.09864 13.8492 3 13.6818 3 13.5V2.5ZM4 3V12.5979L6.97 10.7416C7.29427 10.539 7.70573 10.539 8.03 10.7416L11 12.5979V3H4Z"
                                                    fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <x-wui-badge class="w-fit" icon='users' flat warning
                                                label="{{ $ST_GRClasses }} Group Classes" />
                                            <x-wui-badge class="w-fit" icon='user' flat purple
                                                label="{{ $ST_INDIClasses }} Individual Classes" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-wui-button icon='chat-alt-2' sm label='Message {{ $selectedTutor->user->fname }}' />
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2">
                                {{ $selectedTutor->bio }}
                            </p>
                        </div>

                        {{-- fields card --}}
                        <div class="space-y-2 mt-4">
                            <h2 class="text-lg font-semibold">{{ $selectedTutor->user->fname }}'s Fields</h2>

                            @foreach ($fields = Fields::where('user_id', $selectedTutor->user->id)->get() as $index => $item)
                                @if (in_array($item->field_name, $class_fields))
                                    <x-wui-badge flat rose label="{{ $item->field_name }}" />
                                @else
                                    <x-wui-badge flat slate label="{{ $item->field_name }}" />
                                @endif
                            @endforeach
                        </div>

                        {{-- schedule card --}}
                        <div class="space-y-2 mt-4">
                            <h2 class="text-lg font-semibold">{{ $selectedTutor->user->fname }}'s Schedule</h2>
                            @forelse ($selectedClass as $count => $class)
                                @break($count === 3)
                                <div class="flex justify-between items-start gap-4 p-4 rounded border">

                                    {{-- parent div --}}
                                        <x-wui-icon name='calendar' class="size-6 text-[#0C3B2E]" solid />
                                        <div class="space-y-1 w-full">
                                            {{-- child 1 --}}
                                            <div class="inline-flex items-center gap-2">
                                                <p class="text-[#8F8F8F] font-medium">
                                                    {{ $class->class_name }}
                                                </p>
                                                @if ($class->class_category == 'group')
                                                    <x-wui-badge flat warning
                                                        label="{{ $class->class_category }}" />
                                                @else
                                                    <x-wui-badge flat purple
                                                        label="{{ $class->class_category }}" />
                                                @endif
                                            </div>

                                            {{-- child 2 --}}
                                            <div class="line-clamp-2">
                                                {{ $class->class_description }}
                                            </div>

                                            {{-- child 3 --}}
                                            <div class="flex justify-between items-center">
                                                <div class="text-[#64748B] inline-flex gap-2 items-center">
                                                    <x-wui-icon name='calendar' class="size-5" />
                                                    <p class="font-light text-sm">
                                                        {{ Carbon::create($class->schedule->start_date)->format('l jS \\of F Y h:i A') }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <x-secondary-button>Join Class</x-secondary-button>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            @empty
                                <div class="flex justify-between items-end p-4 rounded border">
                                    No Schedule Yet
                                </div>
                            @endforelse
                        </div>

                        {{-- reviews --}}
                        <div class="space-y-2 mt-4 text-[#0F172A]">
                            <h2 class="text-lg font-semibold">{{ $selectedTutor->user->fname }}'s Reviews</h2>

                            {{-- parent div --}}
                            <div class="flex items-start gap-3">
                                <img class="rounded-full size-10" src="{{ asset('images/default.jpg') }}">
                                <div class="flex flex-col w-full space-y-2">
                                    {{-- reviewer's name --}}
                                    <div class="inline-flex gap-2 text-sm">
                                        <p class="font-semibold">
                                            Santiago López
                                        </p>
                                        <span class="text-[#64748B]">rated 6.5/10</span>
                                    </div>

                                    {{-- reviewer's description --}}
                                    <div x-data="{ isCollapsed: true }">
                                        <div :class="isCollapsed ? 'line-clamp-2 max-h-12' : 'max-h-auto'" class="overflow-hidden">
                                            Miss Helen is an outstanding data structure teacher! Her clear explanations and
                                            engaging teaching style made complex concepts easy to understand. She is
                                            patient, approachable, and always willing to help students grasp the intricacies
                                            of data structures. A truly excellent educator!
                                        </div>

                                        <button @click="isCollapsed = !isCollapsed" class="mt-2 text-xs underline">
                                            <span x-text="isCollapsed ? 'Read More' : 'Show Less'"></span>
                                        </button>
                                    </div>

                                    {{-- review date --}}
                                    <div class="text-[#64748B] inline-flex gap-2 items-center">
                                        <x-wui-icon name='calendar' class="size-5" />
                                        <p class="font-light text-sm">
                                            Posted on January 2, 2024
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center">
                        <img class="size-4/5" src="{{ asset('images/select-tutor.svg') }}" alt="">
                        <p class="text-gray-800">Select a tutor to view its content.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>
</section>
