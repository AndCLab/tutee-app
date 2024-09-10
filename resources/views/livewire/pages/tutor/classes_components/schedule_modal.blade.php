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
                wire:model.blur="sched_initial_date"
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
            <div class="flex flex-col gap-4" x-data="{ tab: window.location.hash ? window.location.hash : 'once' }">
                {{-- Left panel as radio buttons --}}
                <ul class="flex bg-[#F1F5F9] px-1.5 py-1.5 gap-2 rounded-lg">
                    <li class="w-full text-center">
                        <label :class="tab === 'once' ? 'bg-white' : ''"
                            class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out">
                            <input type="radio" wire:model.live='frequency' name="tab" value="once" x-model="tab" class="hidden">
                            Do once
                        </label>
                    </li>
                    <li class="w-full text-center">
                        <label :class="tab === 'every' ? 'bg-white' : ''"
                            class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out">
                            <input type="radio" wire:model.live='frequency' name="tab" value="every" x-model="tab" class="hidden">
                            Do every
                        </label>
                    </li>
                </ul>

                {{-- Right panel --}}
                <div>
                    <div x-show="tab == 'every'" class="space-y-2 mb-3" x-cloak>
                        <div class="gap-2 inline-flex w-full">
                            <x-wui-inputs.maskable
                                wire:model.live='interval'
                                label="Interval"
                                placeholder='Interval'
                                mask="##"
                                shadowless
                                errorless
                            />
                            <x-wui-select
                                wire:model.live='interval_unit'
                                label="Interval Unit"
                                placeholder="Weeks"
                                wire:model.defer="model"
                                shadowless
                                errorless
                            >
                                <x-wui-select.option label="Day/s" value="days" />
                                <x-wui-select.option label="Week/s" value="weeks" />
                                <x-wui-select.option label="Month/s" value="months" />
                            </x-wui-select>
                        </div>
                        <div class="gap-2 inline-flex items-center w-full text-nowrap">
                            <x-wui-inputs.maskable
                                wire:model.live='occurrences'
                                mask="##"
                                label='End After'
                                placeholder='occurrences'
                                shadowless
                                errorless
                            />
                        </div>
                        @if ($interval && $interval_unit && $occurrences && $occurrences > $interval)
                            <x-alert-blue title="{{ $occurrences }} scheduled dates will occur every {{ $interval }} {{ $interval_unit }}.">
                                <div class="gap-2 space-y-2">
                                    @foreach ($generatedDates as $date)
                                        <x-wui-badge flat info label="{{ Carbon::create($date)->format('l, F j, Y') }}" />
                                    @endforeach
                                </div>
                            </x-alert-blue>
                        @endif
                    </div>
                    <x-wui-errors only='sched_initial_date|start_time|end_time|interval|interval_units|occurrences'/>
                </div>
            </div>

        </div>
        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">
                <x-wui-button primary label="Done" spinner='showClassSchedule' x-on:click='close' />
            </div>
        </x-slot>
    </x-wui-card>
</x-wui-modal>
