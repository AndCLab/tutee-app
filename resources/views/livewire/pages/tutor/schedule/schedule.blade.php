<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Schedule;
use App\Models\RecurringSchedule;
use App\Models\Classes;
use App\Models\Fields;

new #[Layout('layouts.app')] class extends Component {

    public $title = 'Schedule | Tutee';
    public $classes;
    public $schedule;

    public $scheduleCardState;
    public $scheduleCardInfo;

    public function mount()
    {
        $user = Auth::user();
        $tutor = Tutor::where('user_id', $user->id)->first();

        $this->classes = Classes::where('tutor_id', $tutor->id)->get();
    }

    public function openModal($id)
    {
        $this->scheduleCardInfo = Classes::find($id);
        $this->scheduleCardState = true;
    }

    public function with(): array
    {
        $events = [];

        foreach ($this->classes as $class) {
            foreach ($class->schedule->recurring_schedule as $recurring) {
                $backgroundColor = $class->class_category == 'group' ? '#fef3c7' : '#f3e8ff';
                $textColor = $class->class_category == 'group' ? '#d97706' : '#9333ea';

                $events[] =  [
                    'id' => $class->id,
                    'url' => route('view-students', $class->id),
                    'title' => $class->class_name,
                    'start' => Carbon::parse($recurring->dates)->format('Y-m-d') . 'T' . Carbon::parse($recurring->schedules->start_time)->format('H:i:s'),
                    'end' => Carbon::parse($recurring->dates)->format('Y-m-d') . 'T' . Carbon::parse($recurring->schedules->end_time)->format('H:i:s'),
                    'backgroundColor' => $backgroundColor,
                    'textColor' => $textColor,
                    'borderColor' => $textColor
                ];

            }
        }

        // dd($events);

        return [
            'events' => $events,
        ];
    }


}; ?>

@push('title')
    {{ $title }}
@endpush

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">

        <div class="md:grid md:grid-row items-start gap-5 pb-3">
            <p class="capitalize font-semibold text-xl">schedules</p>
        </div>

        <div wire:ignore id='calendar'></div>
    </div>

    @if ($scheduleCardState)
        <x-wui-modal.card title="{{ $scheduleCardInfo->class_name }}" blur wire:model="scheduleCardState" persistent align='center' max-width='2xl'>
            <div class="flex justify-between items-start gap-4 p-4 rounded border">

                {{-- parent div --}}
                <x-wui-icon name='calendar' class="size-6 text-[#0C3B2E]" solid />
                <div class="space-y-1 w-full">
                    {{-- child 1 --}}
                    <div class="lg:inline-flex items-center gap-2">
                        <p class="text-[#8F8F8F] font-medium">
                            {{ $scheduleCardInfo->class_name }}
                        </p>
                        @if ($scheduleCardInfo->class_category == 'group')
                            <x-wui-badge flat warning
                                label="{{ $scheduleCardInfo->class_category }}" />
                        @else
                            <x-wui-badge flat purple
                                label="{{ $scheduleCardInfo->class_category }}" />
                        @endif
                    </div>

                    {{-- child 2 --}}
                    <div class="py-3">
                        {{ $scheduleCardInfo->class_description }}
                    </div>

                    {{-- child 3 --}}
                    <div class="lg:flex flex-wrap lg:flex-nowrap lg:justify-between lg:items-center">
                        <div class="text-[#64748B] inline-flex gap-2 items-start">
                            <x-wui-icon name='calendar' class="size-5" />
                            <div class="font-light text-sm">
                                @foreach ($scheduleCardInfo->schedule->recurring_schedule as $recurring)
                                    @if (Carbon::parse($recurring->dates)->isToday() || Carbon::parse($recurring->dates)->isFuture())
                                        <p class="font-medium"> Upcoming Schedule Date: </p>
                                        <span> {{
                                                    Carbon::parse($recurring->dates)->format('l jS \\of F Y') . ' ' .
                                                    Carbon::parse($recurring->schedules->start_time)->format('g:i A')
                                                }}
                                        </span>
                                        @break
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <x-primary-button wire:navigate href="{{ route('view-students', $scheduleCardInfo->id) }}" class="w-full lg:w-fit text-nowrap">
                                View Students
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </div>
        </x-wui-modal.card>
    @endif

    <script>
        document.addEventListener('livewire:navigated', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            eventClick: function(info) {
                var eventObj = info.event;

                if (eventObj.url) {

                    // window.open(eventObj.url);

                    info.jsEvent.preventDefault(); // prevents browser from following link in current tab.

                    @this.openModal(eventObj.id);
                }
            },
            headerToolbar: {
                left: 'prev,next,today',
                center: 'title',
                // right: 'timeGridDay,timeGridFourDay,listDay,listWeek,listMonth'
                right: 'timeGridWeek,listDay,listWeek,listMonth'
            },
            views: {
                listDay: { buttonText: 'list day' },
                listWeek: { buttonText: 'list week' },
                listMonth: { buttonText: 'list month' }
            },
            events: @json($events),
            });

            calendar.render();
        });
    </script>
</section>