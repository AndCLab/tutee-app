@php
    use Carbon\Carbon;
@endphp

@if ($getRecurringDates)
    <x-wui-modal.card title="Current Schedules in this Class" wire:model="showRecurringDates" class="space-y-3" align='center' max-width='md'>

        <p class="text-sm">
            <span class="font-semibold">Time: </span>
            {{ Carbon::create($getClass->schedule->start_time)->format('g:i A') }} -
            {{ Carbon::create($getClass->schedule->end_time)->format('g:i A') }}
        </p>

        @if ($getClass->class_category == 'group' && $getRegistration)
            <div class="my-3 text-sm">
                <p class="gap-2 space-y-2">
                    Registration Start Date: <span class="font-semibold">{{ Carbon::create($getRegistration->start_date)->format('l, F j, Y') }}</span>
                </p>
                <p class="gap-2 space-y-2">
                    Registration End Date: <span class="font-semibold">{{ Carbon::create($getRegistration->end_date)->format('l, F j, Y') }}</span>
                </p>
            </div>
        @endif

        <div class="gap-2 space-y-2">
            @foreach ($getRecurringDates as $date)
                @if(!(Carbon::parse($date->dates)->isPast()))
                    <x-wui-badge flat info label="{{ Carbon::create($date->dates)->format('l, F j, Y') }}" />
                @endif
            @endforeach
        </div>

    </x-wui-modal.card>
@endif
