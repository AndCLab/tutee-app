<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Tutee;
use App\Models\Tutor;
use App\Models\ClassRoster;
use Carbon\Carbon;

new #[Layout('layouts.app')] class extends Component {

    public function with(): array
    {
        $tutee = Tutee::where('user_id', Auth::id())->first();
        $tutor = Tutor::where('user_id', Auth::id())->first();

        if ($tutee) {
            $class_rosters = ClassRoster::with(['classes.schedule.recurring_schedule', 'classes.tutor']) // Eager load tutor
                ->where('tutee_id', $tutee->id)
                ->get();
        } elseif ($tutor){
            $class_rosters = ClassRoster::with(['classes.schedule.recurring_schedule', 'classes.tutor']) // Eager load tutor
                ->whereHas('classes', function ($query) use ($tutor){
                    $query->where('tutor_id', $tutor->id);
                })
                ->get();
        }

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

        return [
            'first_dates' => $first_dates,
            'distinct_dates' => $distinct_dates,
        ];
    }


}; ?>

{{--
    date > now = future
    date < now = past
--}}

<section>
    <x-slot name="header">
    </x-slot>

    <p class="capitalize font-semibold text-xl {{ Auth::user()->user_type == 'tutee' ? 'mb-3' : 'mb-9' }}">upcoming schedule</p>
    @forelse ($distinct_dates as $index => $date)
        @if ($date > Carbon::now()->format('Y-m-d'))
            <div class="space-y-2">
                @foreach ($first_dates as $item)
                    @if ($item['first_date'] === $date)
                        <div class="flex justify-between items-start gap-4">
                            <x-wui-icon name='calendar' class="size-6 text-[#0C3B2E]" solid />
                            <div class="space-y-1 w-full">
                                {{-- class name and category --}}
                                <div class="lg:inline-flex items-center gap-2">
                                    <p class="text-[#8F8F8F] font-medium truncate w-[10rem]">
                                        {{ $item['class_details']->class_name }}
                                    </p>
                                    @if ($item['class_details']->class_category == 'group')
                                        <x-wui-badge flat warning label="{{ $item['class_details']->class_category }}" />
                                    @else
                                        <x-wui-badge flat purple label="{{ $item['class_details']->class_category }}" />
                                    @endif
                                </div>

                                {{-- time --}}
                                <div class="flex flex-col gap-2 w-full">
                                    <div class="text-[#64748B] flex flex-col gap-1 items-start font-light text-sm line-clamp-1">
                                        <span> Date: {{ Carbon::parse($item['first_date'])->format('F d, Y') }}
                                        </span>
                                        <span> Time:
                                            {{ Carbon::parse($item['start_time'])->format('g:i A') }} -
                                            {{ Carbon::parse($item['end_time'])->format('g:i A') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @break
        @endif
    @empty
        <div class="flex flex-col gap-1 justify-center items-center w-full" wire:loading.remove>
            <img class="size-1/2" src="{{ asset('images/empty_schedule.svg') }}" alt="">
            <p class="font-semibold text-base">Empty Schedule</p>
            <span class="font-light text-sm">Find classes in the Discover</span>
        </div>
    @endforelse

</section>
