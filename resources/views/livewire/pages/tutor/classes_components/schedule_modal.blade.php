@php
    use Carbon\Carbon;
@endphp

{{-- class schedule modal --}}
<x-wui-modal wire:model="showClassSchedule" max-width='lg' persistent>
    <x-wui-card title='Class Schedule' wire:loading.class='opacity-60'>
        <div class="grid grid-cols-1 gap-4">
            <x-wui-datetime-picker
                label="Start Date Time"
                placeholder="January 1, 2000"
                wire:model.live="sched_initial_date"
                parse-format="YYYY-MM-DD"
                display-format='dddd, MMMM D, YYYY'
                :min="now()"
                without-time
                shadowless
                errorless
            />
            <div class="inline-flex gap-2">
                <x-wui-time-picker
                    wire:model.live='start_time'
                    label="Start Time"
                    placeholder="12:00 AM"
                    interval="30"
                    shadowless
                    errorless
                />
                <x-wui-time-picker
                    wire:model.live='end_time'
                    label="End Time"
                    placeholder="12:00 PM"
                    interval="30"
                    shadowless
                    errorless
                />
            </div>

            @if ($sched_initial_date && ($start_time && $end_time))

                <div class="grid grid-cols-1 gap-2 {{ $interval_unit != 'once' ? 'md:grid-cols-12' : '' }}">

                    {{-- interval unit --}}
                    @if (Carbon::parse($start_time)->lessThan(Carbon::parse($end_time)))
                        <div class="w-full col-span-7">
                            <x-wui-select
                                wire:model.live='interval_unit'
                                label="Repeat Every"
                                placeholder="Choose your preference"
                                shadowless
                                errorless
                            >
                                <x-wui-select.option label="Once" value="once" />
                                <x-wui-select.option label="Daily" value="days" />
                                <x-wui-select.option label="Weekly" value="weeks" />
                                <x-wui-select.option label="Monthly" value="months" />
                                <x-wui-select.option label="Weekdays (Monday to Friday)" value="weekdays" />
                            </x-wui-select>
                        </div>
                    @endif

                    {{-- stop repeating --}}
                    @if (($interval_unit && $interval_unit != 'once') && ($start_time && $end_time))
                        <div class="w-full col-span-5">
                            <x-wui-select
                                wire:model.live='stop_repeating'
                                label="Stop Repeating Every"
                                placeholder="Choose your preference"
                                shadowless
                                errorless
                            >
                                <x-wui-select.option label="Never" value="never" />
                                <x-wui-select.option label="Date" value="date" />
                            </x-wui-select>
                        </div>
                    @endif
                </div>

                @if ($stop_repeating && $stop_repeating == 'date')
                    {{-- end date --}}
                    <x-wui-datetime-picker
                        label="Schedule End Date"
                        placeholder="Enter End Date"
                        wire:model.live="sched_end_date"
                        parse-format="YYYY-MM-DD"
                        display-format='dddd, MMMM D, YYYY'
                        :min="Carbon::parse($sched_initial_date)"
                        without-tips
                        without-time
                        shadowless
                        errorless
                    />
                @endif
            @endif

            @if ($interval_unit && $stop_repeating && $sched_end_date)
                <x-alert-blue title="{{ empty($generatedDates) ? 'Set your Schedule' : 'Your selected days' }}">
                    <div class="{{ !empty($generatedDates) ? 'pt-3 gap-2 space-y-2' : '' }}">
                        @foreach ($generatedDates as $date)
                            <x-wui-badge flat info label="{{ Carbon::parse($date)->format('l, F j, Y') }}" />
                        @endforeach
                    </div>
                </x-alert-blue>
            @endif

            <x-wui-errors only='sched_initial_date|sched_end_date|start_time|end_time|interval_units'/>

            {{--  @if ($sched_initial_date)
                {{
                    Carbon::parse($sched_initial_date)->diffInDays((clone Carbon::parse($sched_initial_date))->addDays($occurrences))
                }}
            @endif --}}

        </div>
        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">
                <x-wui-button primary label="Done" spinner='showClassSchedule' x-on:click='close' />
            </div>
        </x-slot>
    </x-wui-card>
</x-wui-modal>
