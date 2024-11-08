<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use WireUi\Traits\Actions;
use App\Models\Classes;
use App\Models\ClassRoster;
use App\Models\ReportContent;
use App\Models\RecurringSchedule;
use App\Models\Registration;
use App\Models\Blacklist;
use App\Models\Fields;
use App\Models\Tutee;

new #[Layout('layouts.app')] class extends Component {
    use Actions;

    // properties
    public $getClass;
    public $getRecurringDates;
    public $getRegistration;
    public $selectedOption;
    public $comment;

    // models
    public $report_class;
    public $report_post;

    // states
    public $showReportClassModal;
    public $showReportPostModal;
    public $showClassModal;
    public $showPostModal;
    public $showRecurringDates;
    public $pages = 5;

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

            if ($roster->classes->class_students > 0) {
                $roster->classes->class_students--;
                $roster->classes->save();
            }


            //Add notifications to joined class
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

    public function classView($class_id)
    {
        $this->showClassModal = true;
        $this->getClass = Classes::findOrFail($class_id);
    }

    public function viewRecurringDates()
    {
        $this->showRecurringDates = true;
        $this->getRecurringDates = RecurringSchedule::where('schedule_id', $this->getClass->schedule->id)
                                ->orderBy('dates', 'asc')
                                ->get();
        if ($this->getClass->class_category == 'group') {
            $this->getRegistration = Registration::whereHas('classes', function ($query) {
                                        $query->where('registration_id', $this->getClass->registration_id)
                                            ->orderBy('start_date', 'asc');
                                    })->first();
        }
    }

    public function loadMore()
    {
        $this->pages += 5;
    }

    public function with(): array
    {
        $getFields = array_map('strtolower', Fields::where('user_id', Auth::id())
                           ->where('active_in', Auth::user()->user_type)
                           ->pluck('field_name')
                           ->toArray());

        $classes = Classes::where(function($query) use ($getFields) {
                                foreach ($getFields as $field) {
                                    $query->orWhereRaw('LOWER(class_fields) LIKE ?', "%{$field}%");
                                }
                            })
                            ->whereHas('schedule', function ($query) {
                                $query->where('initial_start_date', '>=', Carbon::now()->format('Y-m-d'));
                            })
                            ->where(function ($query) {
                                // include classes with registrations
                                $query->whereHas('registration', function ($query) {
                                    $query->whereNotNull('start_date')
                                        ->whereNotNull('end_date')
                                        ->where('end_date', '>=', Carbon::now());
                                })
                                // include classes without registrations
                                ->orWhereDoesntHave('registration');
                            })
                            ->where('class_status', '1')
                            ->where('class_students', '>' , 0)
                            ->take($this->pages)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return [
            'classes' => $classes
        ];
    }

    // report class content
    public function reportClassModal($reportClassId)
    {
        $this->showReportClassModal = true;
        $this->report_class = Classes::findOrFail($reportClassId);
    }

    public function submitClassReport()
    {
        $rule = $this->validate([
            'comment' => ['nullable', 'max:255', 'string'],
            'selectedOption' => ['required']
        ], [
            'comment.max' => 'The comment may not be greater than 255 characters.',
            'comment.string' => 'The comment must be a valid string.',
            'selectedOption.required' => 'Please choose a report type.',
        ]);

        $isReported = ReportContent::where('reporter_id', Auth::id())
                                    ->where('class_id', $this->report_class->id)
                                    ->exists();

        if ($isReported) {
            $this->notification([
                'title' => 'Already Reported',
                'description' => 'We\'re still reviewing your feedback.',
                'icon' => 'error',
                'timeout' => 2500,
            ]);

            return;
        }

        $reported = ReportContent::create([
            'reporter_id' => Auth::id(),
            'class_id' => $this->report_class->id,
            'report_option' => $this->selectedOption,
        ]);

        $reported_user_id = $reported->class->tutor->user_id;

        // chgeck if found in blacklist
        $blacklist = Blacklist::where('reported_user_id', $reported_user_id)->first();

        if ($blacklist) {
            // increment if found
            $blacklist->increment('report_count');
        } else {
            // create a new entry with report_count = 1
            Blacklist::create([
                'reported_user_id' => $reported_user_id,
                'report_count' => 1,
            ]);
        }

        if ($this->comment) {
            $reported->comment = $this->comment;
            $reported->save();
        }

        $this->notification([
            'title' => 'Content reported',
            'description' => 'Thank you! We\'ll review your feedback.',
            'icon' => 'success',
            'timeout' => 2500,
        ]);

        $this->showReportClassModal = false;

        $this->reset('comment');
    }

}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    {{-- Class List: Class Cards --}}
    <div class="space-y-6">
        @forelse ($classes as $class)
            <div class="w-full bg-white rounded-lg text-[#0F172A] space-y-6">
                <div class="flex gap-2 items-start">
                    {{-- Tutee Post Creator --}}
                    <div class="size-10">
                        <img
                            alt="User Avatar"
                            src="{{ $class->tutor->user->avatar ? Storage::url($class->tutor->user->avatar) : asset('images/default.jpg') }}"
                            class="rounded-full object-cover border border-[#F1F5F9] overflow-hidden"
                        />
                    </div>
                    <div class="w-full space-y-3">
                        <div class="flex items-center justify-between space-x-3">
                            <div class="flex gap-2 font-semibold">
                                {{ $class->tutor->user->fname .' '. $class->tutor->user->lname}}

                                {{-- Class Category --}}
                                <div>
                                    @if ($class->class_category == 'group')
                                        <span class="text-sm font-light">is looking for students.</span>
                                            <x-wui-badge flat warning label="{{ $class->class_category }}" />
                                    @else
                                        <span class="text-sm font-light">is looking for a student.</span>
                                            <x-wui-badge flat purple label="{{ $class->class_category }}" />
                                    @endif

                                    @foreach (array_filter(json_decode($class->class_fields, true)) as $field)
                                        <x-wui-badge flat gray label="{{ $field }}" />
                                        @break($loop->iteration == 1)
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        {{-- class desc --}}
                        <div class="flex justify-between items-center">

                            <div class="inline-flex items-center gap-3">
                                <p>{{ $class->class_description }}</p>
                            </div>
                        </div>

                        <div class="flex gap-2 items-center text-[#64748B] text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p>Posted on {{ $class->created_at->format('l, F d Y g:i A') }}</p>
                        </div>

                        <hr class="h-px my-8 bg-gray-200 border-0">

                        {{-- buttons --}}
                        <div class="flex items-center space-x-2">
                            <x-secondary-button class="w-full" wire:click="classView({{ $class->id }})" wireTarget="classView({{ $class->id }})">
                                Inspect
                            </x-secondary-button>

                            <x-tertiary-button class="py-[6px] px-4 rounded-md flex items-center">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"
                                    fill="#292D32"  class="icon icon-tabler icons-tabler-filled icon-tabler-message">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M18 3a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-4.724l-4.762 2.857a1 1 0 0 1 -1.508 -.743l-.006 -.114v-2h-1a4 4 0 0 1 -3.995 -3.8l-.005 -.2v-8a4 4 0 0 1 4 -4zm-4 9h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m2 -4h-8a1 1 0 1 0 0 2h8a1 1 0 0 0 0 -2" />
                                </svg>
                            </x-tertiary-button>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="h-px my-2 bg-gray-200 border-0">
        @empty
            <div class="flex flex-col gap-3 justify-center items-center w-full">
                <img class="size-60" src="{{ asset('images/empty_class.svg') }}" alt="">
                <p class="font-semibold text-xl">No classes</p>
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

    <!-- Class View Modal -->
    @include('livewire.pages.tutee.post_components.modals.class_view_modal')

    {{-- report content --}}
    @include('livewire.pages.report_contents.report_class')

    <!-- view recurring schedules -->
    @include('livewire.pages.tutee.post_components.modals.view-recurring-dates')

    <x-wui-notifications position="bottom-right" />

</section>
