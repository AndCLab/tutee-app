<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Schedule;
use App\Models\RecurringSchedule;
use App\Models\ClassRoster;
use App\Models\Classes;
use App\Models\Review;
use App\Models\Fields;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

new #[Layout('layouts.app')] class extends Component {
    use Actions, WithFileUploads;

    // model
    public $payment;
    public $rating;
    public $remarks;
    public $schedule_type;

    // collections
    public $review_class;
    public $class_roster_payment;
    public $class_roster_leave;
    public $attendees;

    // states
    public $payment_modal;
    public $view_payment_modal;
    public $leave_class_modal;
    public $review_class_modal;
    public $view_attendees;

    public function mount()
    {
        $this->schedule_type = 'future';
    }

    public function openPaymentModal($id)
    {
        $this->payment_modal = true;
        $this->class_roster_payment = ClassRoster::findOrFail($id);
    }

    public function viewPayment($id)
    {
        $this->view_payment_modal = true;
        $this->class_roster_payment = ClassRoster::findOrFail($id);
    }

    public function sendPayment()
    {
        $this->validate([
            'payment' => ['required', 'file', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($this->payment) {
            $extension = $this->payment->getClientOriginalExtension();
            $filename = uniqid() . '_' . time() . '.' . $extension;

            $filePath = $this->payment->storeAs('payments', $filename, 'public');

            if ($this->class_roster_payment->payment_status == 'Not Approved') {
                $this->class_roster_payment->payment_status = 'Pending';
            }
            $this->class_roster_payment->proof_of_payment = $filePath;
            $this->class_roster_payment->save();
        }

        $this->notification([
            'title'       => 'Submitted',
            'description' => 'Proof of payment sent!',
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);

        $this->payment_modal = false;
        $this->reset('payment');
    }

    public function openViewAttendees($id)
    {
        $this->view_attendees = true;
        $this->attendees = ClassRoster::where('class_id', $id)->get();
    }

    public function openLeaveClassModal($id)
    {
        $this->leave_class_modal = true;
        $this->class_roster_leave = ClassRoster::findOrFail($id);
    }

    public function leaveClass()
    {
        $this->class_roster_leave->classes->class_students++;
        $this->class_roster_leave->classes->save();

        $this->class_roster_leave->delete();

        $this->notification([
            'title'       => 'Success',
            'description' => 'You left!',
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);

        // Get the current authenticated tutee
        $tutee_id = Tutee::where('user_id', Auth::id())->pluck('id')->first();
        // Retrieve the class associated with the class roster
        $class = $this->class_roster_leave->classes;

        // Dispatch the leave class event
        $this->dispatch('class-left', [
            'tutee_id' => $tutee_id,
            'class_id' => $class->id,
            'tutor_id' => $class->tutor_id, // Pass the tutor ID for notifications
        ]);

        $this->leave_class_modal = false;
    }

    public function reviewClassModal($id)
    {
        $this->review_class_modal = true;
        $this->review_class = Classes::findOrFail($id);
    }

    public function reviewClass()
    {
        $this->validate([
            'rating' => ['required', 'integer'],
            'remarks' => ['required', 'string']
        ]);

        $tutee = Tutee::where('user_id', Auth::id())->first();

        Review::create([
            'reviewer' => $tutee->id,
            'class_id' => $this->review_class->id,
            'rating' => $this->rating,
            'remarks' => $this->remarks
        ]);

        $allReviews = Review::where('class_id', $this->review_class->id)->pluck('rating')->toArray();

        if (count($allReviews) > 0) {
            $totalReview = array_sum($allReviews);
            $averageRating = $totalReview / count($allReviews);

            Tutor::findOrFail($this->review_class->tutor->id)->update(['average_rating' => $averageRating]);
        } else {
            Tutor::findOrFail($this->review_class->tutor->id)->update(['average_rating' => $this->rating]);
        }

        ClassRoster::where('class_id', $this->review_class->id)
                    ->where('tutee_id', $tutee->id)
                    ->first()
                    ->update(['rated' => 1]);

        $this->notification([
            'title'       => 'Review Submitted',
            'description' => 'Thank you for your review on this class!',
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);

        $this->review_class_modal = false;
    }

    public function setSchedule($schedule_type)
    {
        $this->schedule_type = $schedule_type;
    }


    public function with(): array
    {
        $tutee = Tutee::where('user_id', Auth::id())->first();

        $getFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])
                                ->toArray();

        $allTutors = Tutor::whereHas('user.fields', function ($query) use ($getFields){
                                $query->whereIn('field_name', $getFields);
                            })
                            ->orderBy('average_rating', 'desc')->get();


        $class_rosters = ClassRoster::with(['classes.schedule.recurring_schedule', 'classes.tutor']) // Eager load tutor
            ->where('tutee_id', $tutee->id)
            ->get();

        // Get the first date for each class roster
        $first_dates = $class_rosters->map(function ($class_roster) {
            return [
                'class_roster_details' => $class_roster,
                'class_roster_id' => $class_roster->id,
                'class_details' => $class_roster->classes,
                'tutor' => $class_roster->classes->tutor,
                'first_date' => $class_roster->classes->schedule->recurring_schedule->min('dates'),
                'start_time' => $class_roster->classes->schedule->start_time,
                'end_time' => $class_roster->classes->schedule->end_time,
            ];
        })->sortBy(function ($item) {
            // Create a composite key for sorting by date and then by start time
            return [
                $item['first_date'],
                $item['start_time']
            ];
        })->values(); // If you want to re-index the sorted collection

        $classes_grouped_by_date = $first_dates->groupBy('first_date');
        $distinct_dates = $classes_grouped_by_date->keys()->sort()->values();

        // Check if all classes for each distinct date are rated
        $all_rated_status = [];
        foreach ($distinct_dates as $date) {
            $rosters_for_date = $classes_grouped_by_date[$date];
            $all_rated = $rosters_for_date->every(function ($item) {
                return $item['class_roster_details']->rated;
            });

            $all_rated_status[$date] = $all_rated; // Store the result
        }

        return [
            'class_rosters' => $class_rosters,
            'first_dates' => $first_dates,
            'distinct_dates' => $distinct_dates,
            'all_rated_status' => $all_rated_status,
            'allTutors' => $allTutors,
        ];
    }



}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">
        <div class="lg:grid lg:grid-cols-3 items-start gap-5">
            {{-- Schedule --}}
            <div class="lg:col-span-2 space-y-3">
                {{-- Header --}}
                <div class="w-full inline-flex justify-between items-center mb-5">
                    <p class="capitalize font-semibold text-xl">your schedule</p>
                    <x-primary-button class="text-xs" wire:click="setSchedule('{{ $schedule_type == 'future' ? 'past' : 'future' }}')">
                        {{ $schedule_type == 'future' ? 'View Past Schedules' : 'View Upcoming Schedules' }}
                    </x-primary-button>
                </div>

                <ol @class([
                        'sm:border-s sm:border-gray-200 sm:relative' => $first_dates->isNotEmpty(),
                    ])>

                    @forelse ($distinct_dates as $index => $date)
                        {{-- check if ni labay nga schedule pero wa pa na rate sa studyante --}}
                        @php
                            $isFuture = !$all_rated_status[$date] && $schedule_type == 'future';
                            $isPast = $date < Carbon::now()->format('Y-m-d') && $schedule_type == 'past';
                        @endphp

                        @if ($isFuture)
                            <li class="mb-10 sm:ms-6">
                                <span class="sm:absolute -start-3 sm:flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 ring-8 ring-white dark:bg-blue-900 dark:ring-gray-900">
                                    <x-wui-icon name='calendar' class="hidden sm:block size-2.5 text-[#0C3B2E]" solid />
                                </span>
                                <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ Carbon::parse($date)->format('l, F d, Y') }}
                                </h3>
                                <div class="space-y-3">
                                    @include('livewire.pages.tutee.schedule.schedule-card')
                                </div>
                            </li>
                        @elseif ($isPast)
                            <li class="mb-10 sm:ms-6">
                                <span class="sm:absolute -start-3 sm:flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 ring-8 ring-white dark:bg-blue-900 dark:ring-gray-900">
                                    <x-wui-icon name='calendar' class="hidden sm:block size-2.5 text-[#0C3B2E]" solid />
                                </span>
                                <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ Carbon::parse($date)->format('l, F d, Y') }}
                                </h3>
                                <div class="space-y-3">
                                    @include('livewire.pages.tutee.schedule.schedule-card')
                                </div>
                            </li>
                        @endif
                    @empty
                        <div class="flex flex-col gap-2 justify-center items-center w-full">
                            <img class="size-1/2" src="{{ asset('images/empty_schedule.svg') }}" alt="">
                            <p class="font-semibold text-xl">You don't have any schedule set up yet.</p>
                            <span class="font-light">Find classes in the Discover</span>
                        </div>
                    @endforelse
                </ol>
            </div>

            {{-- Top Tutors --}}
            <div class="hidden lg:block space-y-3 sticky top-[5rem] overflow-y-auto max-h-[85vh] soft-scrollbar px-2 pb-3">
                <livewire:pages.tutee.components.top-tutors>
            </div>
        </div>
    </div>

    @include('livewire.pages.tutee.schedule.payment-modal')
    @include('livewire.pages.tutee.schedule.view-payment')
    @include('livewire.pages.tutee.schedule.leave-class-modal')
    @include('livewire.pages.tutee.schedule.view-attendees')
    @include('livewire.pages.tutee.schedule.review-class')

    <x-wui-notifications position="bottom-right" />
</section>
