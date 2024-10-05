@php
    use Carbon\Carbon;
@endphp

{{-- class schedule modal --}}
{{--
    logic:
        if 3 ka scheduled dates every 2 weeks kay like

        1. Sept 2
        2. Sept 16
        3. Sept 30
--}}

<x-wui-modal wire:model="showClassSchedule" max-width='lg' persistent>
    <x-wui-card title='Class Schedule'>
        <div class="grid grid-cols-1 gap-4">
            <x-wui-datetime-picker
                label="Start Date Time"
                placeholder="January 1, 2000"
                wire:model.live="sched_initial_date"
                parse-format="YYYY-MM-DD HH:mm"
                display-format='dddd, MMMM D, YYYY'
                :min="now()"
                without-time
                shadowless
                errorless
            />
            <div class="inline-flex gap-2">
                <x-wui-time-picker
                    wire:model.blur='start_time'
                    label="Start Time"
                    placeholder="12:00 AM"
                    interval="30"
                    shadowless
                    errorless
                />
                <x-wui-time-picker
                    wire:model.blur='end_time'
                    label="End Time"
                    placeholder="12:00 PM"
                    interval="30"
                    shadowless
                    errorless
                />
            </div>

            @if ($sched_initial_date && $start_time && $end_time)
                {{-- Right panel --}}
                <div class="w-full">
                    <x-wui-select
                        wire:model.live='interval_unit'
                        label="Repeat Every"
                        placeholder="Start Date: {{ Carbon::create($sched_initial_date)->toFormattedDateString() }}"
                        shadowless
                        errorless
                    >
                        <x-wui-select.option label="Once" value="once" />
                        <x-wui-select.option label="Daily" value="days" />
                        <x-wui-select.option label="Weekly" value="weeks" />
                        <x-wui-select.option label="Monthly" value="months" />
                    </x-wui-select>
                </div>

                @if (isset($interval_unit) && $interval_unit != 'once')
                    <div class="w-full">
                        <x-wui-select
                            wire:model.live='stop_repeating'
                            label="Stop Repeating Every"
                            placeholder="Start Date: {{ Carbon::create($sched_initial_date)->toFormattedDateString() }}"
                            shadowless
                            errorless
                        >
                            <x-wui-select.option label="Never" value="never" />
                            <x-wui-select.option label="Date" value="days" />
                            <x-wui-select.option label="Occurences" value="occurences" />
                        </x-wui-select>
                    </div>
                @endif

                @if (isset($stop_repeating) && $stop_repeating == 'days')
                    <x-wui-datetime-picker
                    label="Schedule End Time"
                    placeholder="Enter End Date"
                    wire:model.live="sched_end_date"
                    parse-format="YYYY-MM-DD HH:mm"
                    display-format='dddd, MMMM D, YYYY'
                    :min="Carbon::parse($sched_initial_date)"
                    without-tips
                    without-time
                    shadowless
                    errorless
                />
                @endif

                @if (isset($stop_repeating) && $stop_repeating == 'occurences')
                    <x-wui-inputs.maskable
                        wire:model.live='occurrences'
                        label="Occurrences"
                        mask="##"
                        placeholder="Enter Occurences"
                        shadowless
                    />
                @endif
            @endif

            @if ($interval && $interval_unit && $occurrences)
                <x-alert-blue title="{{ $occurrences }} scheduled dates will occur every {{ $interval }} {{ $interval_unit }}.">
                    <div class="gap-2 space-y-2">
                        @foreach ($generatedDates as $date)
                            <x-wui-badge flat info label="{{ Carbon::create($date)->format('l, F j, Y') }}" />
                        @endforeach
                    </div>
                </x-alert-blue>
            @endif

            <x-wui-errors only='sched_initial_date|start_time|end_time|interval|interval_units|occurrences'/>

        </div>
        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">
                <x-wui-button primary label="Done" spinner='showClassSchedule' x-on:click='close' />
            </div>
        </x-slot>
    </x-wui-card>
</x-wui-modal>
