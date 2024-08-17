<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Carbon\Carbon;
use App\Models\Tutor;
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

    public $sort_by;

    public function mount()
    {
        $this->tutors = Tutor::take($this->pages)->get();
        $this->selectedTutor = null;
    }

    public function loadMore()
    {
        $this->pages += 5;
        $this->tutors = Tutor::take($this->pages)->get();
    }

    public function selectTutor($tutor_id)
    {
        $this->selectedTutor = Tutor::findOrFail($tutor_id);
        $this->show_tutor = $this->selectedTutor->user->fname;

        $this->ST_INDIClasses = Classes::where('tutor_id', $tutor_id)->where('class_category', 'individual')->count();
        $this->ST_GRClasses = Classes::where('tutor_id', $tutor_id)->where('class_category', 'group')->count();

        $this->selectedClass = Classes::where('tutor_id', $tutor_id)->get();
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
        <div class="lg:grid lg:grid-row items-start gap-5">
            <p class="capitalize font-semibold text-xl">Find Tutors</p>

            {{-- Class List: Search and Filter --}}
            <div class="flex gap-2 pb-3">
                <div class="w-full">
                    <x-wui-input wire:model.live='search' placeholder='Search class...' icon='search' shadowless />
                </div>

                <div class="w-fit">
                    <x-wui-select wire:model.live='sort_by' placeholder="Sort by" shadowless>
                        <x-wui-select.option label="Ascending" value="asc" />
                        <x-wui-select.option label="Descending" value="desc" />
                    </x-wui-select>
                </div>
            </div>
        </div>

        <section class="grid grid-cols-10 gap-2 items-start" x-data="{ active: null }" x-cloak>

            {{-- left panel --}}
            <div class="col-span-4 space-y-2">

                {{-- tutor cards --}}
                @foreach ($tutors as $tutor)
                    <div class="p-2 rounded cursor-pointer hover:bg-gray-100"
                            :class="{ 'outline-1 outline outline-[#0C3B2E]': active === {{ $tutor->id }} }"
                            wire:click="selectTutor({{ $tutor->id }})"
                            x-on:click="active = {{ $tutor->id }}"
                        >
                        <div class="flex justify-between">
                            <div class="flex gap-4">
                                @if ($tutor->user->avatar !== null)
                                    <img class="border-2 border-[#F1F5F9] overflow-hidden rounded-md size-14" src="{{ Storage::url($tutor->user->avatar) }}">
                                @else
                                    <img class="border-2 border-[#F1F5F9] overflow-hidden rounded-md size-14" src="{{ asset('images/default.jpg') }}">
                                @endif
                                <p class="text-sm font-medium">{{ $tutor->user->fname . ' ' . $tutor->user->lname }}</p>
                            </div>
                            <x-wui-button.circle teal flat @click="alert('test')" icon='bookmark' />
                        </div>
                        <div class="space-y-2">
                            @foreach ($fields = Fields::where('user_id', $tutor->user->id)->get() as $index => $item)
                                @if ($index != 3)
                                    <x-wui-badge flat slate label="{{ $item->field_name }}" />
                                @else
                                    @break
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <div x-intersect.full='$wire.loadMore()'>
                    <div wire:loading wire:target="loadMore">
                        skeleton load for infinite scrolling
                    </div>
                </div>
            </div>

            {{-- right panel --}}
            <div class="col-span-6 sticky top-[5.2rem] p-6 bg-white rounded border overflow-y-auto max-h-[85vh] soft-scrollbar">
                @if ($selectedTutor)
                    <div wire:loading wire:target="selectTutor">
                        skeleton load for selected tutor
                    </div>
                    <div wire:loading.remove wire:target="selectTutor">

                        {{-- profile and bio --}}
                        <div>
                            <div class="flex gap-4">
                                @if ($tutor->user->avatar !== null)
                                    <img class="rounded-md size-24" src="{{ Storage::url($tutor->user->avatar) }}">
                                @else
                                    <img class="rounded-md size-24" src="{{ asset('images/default.jpg') }}">
                                @endif
                                <div class="flex justify-between w-full">
                                    <div class="space-y-2">
                                        <h2 class="text-xl font-semibold">{{ $tutor_name }}</h2>
                                        <div class="flex flex-col gap-2">
                                            <x-wui-badge class="w-fit" icon='users' flat warning
                                                label="{{ $ST_GRClasses }} Group Classes" />
                                            <x-wui-badge class="w-fit" icon='user' flat purple
                                                label="{{ $ST_INDIClasses }} Individual Classes" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-wui-button icon='chat-alt-2' sm label='Message {{ $tutor->user->fname }}'/>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2">A dedicated data structure teacher with a passion for imparting knowledge.
                                With a
                                wealth of experience in the field, I bring a unique blend of expertise and
                                enthusiasm to the classroom.</p>
                        </div>

                        {{-- fields card --}}
                        <div class="space-y-2 mt-4">
                            <h2 class="text-lg font-semibold">{{ $selectedTutor->user->fname }}'s Fields</h2>

                            @foreach ($fields = Fields::where('user_id', $selectedTutor->user->id)->get() as $index => $item)
                                <x-wui-badge flat slate label="{{ $item->field_name }}" />
                            @endforeach
                        </div>

                        {{-- schedule card --}}
                        <div class="space-y-2 mt-4">
                            <h2 class="text-lg font-semibold">{{ $selectedTutor->user->fname }}'s Schedule</h2>

                            @if ($selectedClass->isNotEmpty())
                                @foreach ($selectedClass as $class)
                                    <div class="flex justify-between items-end p-4 rounded border">

                                        {{-- parent div --}}
                                        <div class="flex gap-4 items-start">
                                            <x-wui-icon name='calendar' class="size-6 text-[#0C3B2E]" solid />
                                            <div class="space-y-1">
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
                                                <div class="text-[#64748B] inline-flex gap-2 items-center">
                                                    <x-wui-icon name='calendar' class="size-5" />
                                                    <p class="font-light text-sm">
                                                        {{ Carbon::create($class->schedule->start_date)->format('l jS \\of F Y h:i A') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <x-secondary-button>Join Class</x-secondary-button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex justify-between items-end p-4 rounded border">
                                    No Schedule Yet
                                </div>
                            @endif
                        </div>

                        {{-- reviews --}}
                        <div class="space-y-2 mt-4">
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
                                    <div class="line-clamp-2">
                                        Miss Helen is an outstanding data structure teacher! Her clear explanations and
                                        engaging teaching style made complex concepts easy to understand. She is
                                        patient, approachable, and always willing to help students grasp the intricacies
                                        of data structures. A truly excellent educator!"
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
                    {{-- <div class="animate-pulse">
                        <div>
                            <div class="flex gap-4">
                                <div class="size-24 rounded-md bg-[#D4E0EC]"></div>
                                <div class="flex justify-between w-full">
                                    <div class="space-y-2">
                                        <div class="inline-flex items-center gap-2">
                                            <div class="w-40 h-6 rounded bg-[#D4E0EC]"></div>
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <div class="w-20 h-3 rounded bg-[#D4E0EC]"></div>
                                            <div class="w-20 h-3 rounded bg-[#D4E0EC]"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="w-20 h-6 rounded bg-[#D4E0EC]"></div>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 w-40 h-6 rounded bg-[#D4E0EC]"></p>
                        </div>
                    </div> --}}

                    <div class="flex flex-col items-center justify-center">
                        <img class="size-4/5" src="{{ asset('images/select-tutor.svg') }}" alt="">
                        <p class="text-gray-800">Select a tutor to view its content.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>
</section>
