{{-- resources\views\livewire\pages\tutee\tutors.blade.php --}}
<?php

use Illuminate\Support\Facades\Auth;

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use WireUi\Traits\Actions;
use Carbon\Carbon;
use App\Models\Tutor;
use App\Models\Tutee;
use App\Models\User;

use App\Models\Classes;
use App\Models\ClassRoster;
use App\Models\Review;
use App\Models\Fields;
use App\Models\Bookmark;
use App\Events\ClassJoined;

new #[Layout('layouts.app')] class extends Component {
    use Actions;

    public $showClassModal = false; // Controls the visibility of the modal
    public $getClass; // Holds the class details

    protected $listeners = ['selectTutor'];

    public $title = 'Tutors | Tutee';

    public $pages = 5;

    public $mobileViewTutor;
    public $selectedTutor;
    public $selectedClass;
    public $selectedReview;
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

    public $isBookmarked;
    public $tutorIdForBookmark;
    public $isHovered=false;

    public function mount()
    {

        $tutor_id = request()->query('tutor_id');
        if ($tutor_id) {
            $this->selectTutor($tutor_id);
        }

        $this->getFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])
                                ->toArray();
        $this->selectedTutor = null;
    }

    public function loadMore()
    {
        $this->pages += 5;
    }

    public function selectTutor($tutor_id)
    {
        $this->selectedTutor = Tutor::findOrFail($tutor_id);
        $this->show_tutor = $this->selectedTutor->user->fname;

        $this->ST_INDIClasses = Classes::where('tutor_id', $tutor_id)->where('class_category', 'individual')->count();
        $this->ST_GRClasses = Classes::where('tutor_id', $tutor_id)->where('class_category', 'group')->count();

        $this->selectedClass = Classes::where('tutor_id', $tutor_id)->get();

        $this->selectedReview = Review::whereHas('classes', function ($query) {
                                $query->where('tutor_id', $this->selectedTutor->id);
                            })->get();
                            $this->tutorIdForBookmark = $tutor_id;

        $this->isBookmarked = Bookmark::where('tutor_id', $tutor_id)
                            ->where('user_id', Auth::id())
                            ->exists();
    }


    public function toggleBookmark()
    {
        if ($this->isBookmarked) {
            // Remove bookmark
            Bookmark::where('tutor_id', $this->tutorIdForBookmark)
                ->where('user_id', Auth::id())
                ->delete();
            $this->isBookmarked = false;
        } else {
            // Add bookmark
            Bookmark::create([
                'tutor_id' => $this->tutorIdForBookmark,
                'user_id' => Auth::id(),
            ]);
            $this->isBookmarked = true;
        }
    }

    public function isTutorBookmarked($tutorId)
    {
        // Check if the current user has bookmarked the tutor
        return Bookmark::where('user_id', Auth::id())
                    ->where('tutor_id', $tutorId)
                    ->exists();
    }


    public function with(): array
    {
        $tutors = Tutor::when($this->search, function ($q) {
                                $q->whereHas('user', function ($query) {
                                    $query->where('fname', 'like', "%{$this->search}%")
                                        ->orWhere('lname', 'like', "%{$this->search}%");
                            });
                            })
                            ->when($this->sort_by, function ($q) {
                                $q->orderBy('created_at', $this->sort_by);
                            })
                            ->when($this->time_avail, function ($q) {
                                $q->whereHas('classes.schedule.recurring_schedule', function ($query) {
                                    $query->where('dates', '=', $this->time_avail);
                                });
                            })
                            ->when($this->class_fields, function ($q) {
                                $lowercaseFields = array_map('strtolower', $this->class_fields);

                                $q->whereHas('user.fields', function ($query) use ($lowercaseFields) {
                                    $query->whereIn('field_name', $lowercaseFields);
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

        return [
            'tutors' => $tutors,
        ];
    }

    public function classView($class_id)
    {
        $this->showClassModal = true;
        $this->getClass = Classes::findOrFail($class_id);
    }

    public function joinClass()
    {
        $tutee_id = Tutee::where('user_id', Auth::id())->pluck('id')->first();

        $checkIfAlreadyJoined = ClassRoster::where('class_id', $this->getClass->id)
                                            ->where('tutee_id', $tutee_id)
                                            ->exists();

        if (!$checkIfAlreadyJoined) {
            $roster = ClassRoster::create([
                'class_id' => $this->getClass->id,
                'tutee_id' => $tutee_id,
            ]);

            // Update class student count
            if ($roster->classes->class_students > 0) {
                $roster->classes->class_students--;
                $roster->classes->save();
            }

            $specificDate = $this->getClass->schedule->recurring_schedule->first()->dates ?? null; // Get the first date
            $this->dispatch('class-joined', [
                'tutee_id' => $tutee_id,
                'class_id' => $this->getClass->id,
                'schedule_id' => $this->getClass->schedule_id,
                'specific_date' => $specificDate // Pass the specific date
            ]);


            $this->notification([
                'title'       => 'Success',
                'description' => 'Successfully Joined Class',
                'icon'        => 'success',
                'timeout'     => 2500,
            ]);

        } else {
            $this->notification([
                'title'       => 'Schedule Duplication',
                'description' => 'Already Joined this Class',
                'icon'        => 'error',
                'timeout'     => 2500,
            ]);
        }

        $this->showClassModal = false;
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
        <div class="md:grid md:grid-row items-start gap-5 pb-3">
            <p class="capitalize font-semibold text-xl">Find Tutors</p>

            {{-- Class List: Search and Filter --}}

            <div class="grid md:grid-cols-8 gap-2">
                <div class="space-y-2 md:col-span-5">
                    {{-- search --}}
                    <div class="w-full">
                        <x-wui-input wire:model.live='search' placeholder='Search tutor...' icon='search' shadowless />
                    </div>

                    <div class="md:inline-flex space-y-2 md:space-y-0 gap-2">
                        {{-- pricing --}}
                        <div class="w-full md:w-fit">
                            <x-wui-inputs.currency wire:model.live.debounce.250ms='pricing' icon="cash" placeholder="Pricing" shadowless />
                        </div>

                        {{-- sort by --}}
                        <div class="w-full md:w-fit">
                            <x-wui-select wire:model.live='sort_by' placeholder="Sort by" shadowless>
                                <x-wui-select.option label="Ascending" value="asc" />
                                <x-wui-select.option label="Descending" value="desc" />
                            </x-wui-select>
                        </div>

                        {{-- tutor type --}}
                        <div class="w-full md:w-fit">
                            <x-wui-select wire:model.live='tutor_type' placeholder="Tutor Type" shadowless>
                                <x-wui-select.option label="Accredited" value="accre" />
                                <x-wui-select.option label="Non-Accredited" value="non_accre" />
                            </x-wui-select>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2 pb-3 md:col-span-3">

                    {{-- class fields --}}
                    <div class="w-full">
                        <x-wui-select wire:model.live="class_fields" placeholder="Class Fields" multiselect shadowless>
                            @foreach ($getFields as $field)
                                <x-wui-select.option label="{{ $field['field_name'] }}"
                                    value="{{ $field['field_name'] }}" />
                            @endforeach
                        </x-wui-select>
                    </div>

                    {{-- time avail --}}
                    <div class="w-full">
                        <x-wui-datetime-picker
                            placeholder="Availability"
                            wire:model.live="time_avail"
                            parse-format="YYYY-MM-DD"
                            display-format='dddd, MMMM D, YYYY'
                            :min="now()"
                            without-time
                            shadowless
                        />
                    </div>
                </div>
            </div>

        </div>

        <section class="grid grid-cols-1 md:grid-cols-10 gap-2 items-start" x-data="{ active: null }" x-cloak>

            {{-- left panel --}}
            <div class="md:col-span-4 space-y-3">
                {{-- tutor cards --}}
                @forelse ($tutors as $tutor)
                    <div class="p-2 rounded cursor-pointer hover:bg-[#F2F2F2]"
                        :class="{ 'border-l-2 rounded-l-none border-[#0C3B2E]': active === {{ $tutor->id }} }"
                        wire:click="selectTutor({{ $tutor->id }})"
                        onclick="selectTutorWithMobileCheck({{ $tutor->id }})"
                        x-on:click="active = {{ $tutor->id }}">

                        {{-- new tutor identifier --}}
                        @php
                            $week = Carbon::parse($tutor->created_at)->addWeek();
                        @endphp

                        <div class="mb-2">
                            @if (Carbon::now()->lessThan($week))
                                <x-wui-badge flat indigo label="New Tutor" />
                            @endif
                        </div>

                        {{-- profile pic and name --}}
                        <div class="flex flex-wrap gap-3 items-start">
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
                                    <span class="text-xs text-[#64748B]">
                                        @php
                                            $degrees = json_decode($tutor->degree, true);
                                        @endphp
                                        {{ is_array($degrees) ? implode(', ', $degrees) : $tutor->degree }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- tutor class fields --}}
                        <div class="space-y-2">
                            @foreach ($fields = Fields::where('user_id', $tutor->user->id)->get() as $index => $item)
                                @break ($index === 3)
                                @if (in_array(strtolower($item->field_name), array_map('strtolower', $class_fields)))
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
            <div class="hidden md:block md:col-span-6 sticky top-[5rem] p-6 bg-white rounded border overflow-y-auto max-h-[85vh] soft-scrollbar">
                @if ($selectedTutor)
                    @include('livewire.pages.tutee.tutor_components.selected_tutor')
                @else
                    <div class="flex flex-col items-center justify-center">
                        <img class="size-4/5" src="{{ asset('images/select-tutor.svg') }}" alt="">
                        <p class="text-gray-800">Select a tutor to view its content.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>

    {{-- modal card for mobile view --}}
    <x-wui-modal.card class="hidden hide-scrollbar" wire:model="mobileViewTutor" fullscreen>
        @if ($selectedTutor)
            @include('livewire.pages.tutee.tutor_components.selected_tutor')
        @endif

        <x-slot name="footer">
            <x-wui-button flat label="Cancel" x-on:click="close" />
        </x-slot>
    </x-wui-modal.card>

    {{-- will allow to set the mobileViewTutor if the width of the screen is within mobile width :) --}}
    <script>
        function selectTutorWithMobileCheck(tutor_id) {
            if (window.innerWidth <= 768) {
                @this.mobileViewTutor = true;
            }
            @this.selectTutor(tutor_id);
        }
    </script>

    <x-wui-notifications position="bottom-right" />
</section>
