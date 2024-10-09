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

                <div class="grid grid-cols-1 gap-2 {{ $interval_unit != 'once' && $interval_unit != 'custom' ? 'md:grid-cols-12' : '' }}">

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
                                <x-wui-select.option label="Custom Schedule" value="custom" />
                            </x-wui-select>
                        </div>
                    @endif

                    {{-- stop repeating --}}
                    @if (($interval_unit && ($interval_unit != 'once' && $interval_unit != 'custom')) && ($start_time && $end_time))
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

        </div>
        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">
                <x-wui-button primary label="Done" spinner='showClassSchedule' x-on:click='close' />
            </div>
        </x-slot>
    </x-wui-card>
</x-wui-modal>

{{-- custom modal --}}
<x-wui-modal wire:model="customModal" max-width='lg' persistent blur='sm'>
    <x-wui-card title='Class Schedule' wire:loading.class='opacity-60'>
        <div class="space-y-4">

            <div class="flex-col gap-2">
                <p class="text-sm antialiased">Initial Date: {{ Carbon::parse($sched_initial_date)->toFormattedDateString() }}</p>
                <p class="text-sm antialiased">From: {{ Carbon::parse($start_time)->format('g:i A') }} - {{ Carbon::parse($end_time)->format('g:i A') }}</p>
            </div>

            {{-- custom end date --}}
            <x-wui-datetime-picker
                label="Schedule End Date"
                placeholder="Enter End Date"
                wire:model.live="customEndDate"
                parse-format="YYYY-MM-DD"
                display-format='dddd, MMMM D, YYYY'
                :min="Carbon::parse($sched_initial_date)"
                without-tips
                without-time
                shadowless
                errorless
            />

            @php
                $weeks = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            @endphp
            @if ($customEndDate)
                <ul class="flex flex-row gap-2 justify-center w-full">
                    @foreach ($weeks as $week)
                        <li wire:click.prevent='getCustomWeek("{{ $week }}")'>
                            <input type="checkbox" id="{{ $week }}" value="{{ $week }}" class="peer hidden"
                                @if (in_array($week, $frequency)) checked @endif />
                            <label for="{{ $week }}" class="inline-flex cursor-pointer w-14 items-center justify-between rounded-lg border border-[#CBD5E1] bg-white p-2 text-black hover:bg-gray-100 hover:text-gray-600 peer-checked:border-[#0C3B2E] peer-checked:text-[#0C3B2E] peer-checked:hover:bg-white transition-colors duration-150">
                                <div class="mx-auto">
                                    <div class="w-full text-center text-sm font-medium">{{ $week }}</div>
                                </div>
                            </label>
                        </li>
                    @endforeach
                </ul>
            @endif

            {{-- @dump($frequency) --}}

            <x-alert-blue title="{{ empty($generatedDates) ? 'Set your Schedule' : 'Your selected days' }}">
                <div class="{{ !empty($generatedDates) ? 'pt-3 gap-2 space-y-2' : '' }}">
                    @foreach ($generatedDates as $date)
                        <x-wui-badge flat info label="{{ Carbon::parse($date)->format('l, F j, Y') }}" />
                    @endforeach
                </div>
            </x-alert-blue>

            <x-wui-errors only='sched_initial_date|sched_end_date|start_time|end_time|interval_units'/>

        </div>
        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">
                <x-wui-button primary label="Done" spinner='showClassSchedule' x-on:click='close' />
            </div>
        </x-slot>
    </x-wui-card>
</x-wui-modal>
