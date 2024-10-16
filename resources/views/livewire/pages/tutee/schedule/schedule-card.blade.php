@php
    use Carbon\Carbon;
    use App\Models\ClassRoster;
@endphp

@forelse ($first_dates as $item)
    {{-- @if ($schedule == 'future')
        @continue($date < Carbon::now()->format('Y-m-d') && $item['class_roster_details']->rated)
    @endif --}}

    @if ($item['first_date'] === $date)
        {{-- $date > Carbon::parse($item['first_date']) --}}
        <div class="flex flex-col">

            {{-- container --}}
            <div @class([
                'rounded border p-4 space-y-2',
                'border-info-500' => $date < Carbon::now()->format('Y-m-d') && $item['class_roster_details']->payment_status == 'Approved',
                'border-red-500' => $date < Carbon::now()->format('Y-m-d') && ($item['class_roster_details']->payment_status == 'Not Approved' || $item['class_roster_details']->payment_status == 'Pending')
                ]) x-data="{ expanded: false }">
                <div class="flex justify-between items-start gap-4">

                    {{-- parent div --}}
                    <div class="space-y-1 w-full">
                        {{-- class name and category --}}
                        <div class="flex w-full items-center justify-between gap-2">
                            <div class="lg:inline-flex gap-2 items-center">
                                <p class="text-[#8F8F8F] font-medium">
                                    {{ $item['class_details']->schedule->id }}
                                    {{ $item['class_details']->id }}
                                    {{ $item['class_roster_id'] }}
                                    {{ $item['class_details']->class_name }}
                                </p>
                                @if ($item['class_details']->class_category == 'group')
                                    <x-wui-badge flat warning label="{{ $item['class_details']->class_category }}" />

                                    {{-- avatar group --}}
                                    <div class="flex -space-x-2 items-center">
                                        @php
                                            $attendees = ClassRoster::where('class_id', $item['class_details']->id)->get();
                                        @endphp

                                        @foreach ($attendees as $index => $attendee)
                                            <div
                                                class="shrink-0 inline-flex items-center justify-center overflow-hidden rounded-full border border-gray-200 dark:border-secondary-500">
                                                @if ($attendee->tutees->user->avatar)
                                                    <img class="shrink-0 object-cover object-center rounded-full w-8 h-8 text-sm" src="{{ Storage::url($attendee->tutees->user->avatar) }}">
                                                @else
                                                    <img class="shrink-0 object-cover object-center rounded-full w-8 h-8 text-sm" src="{{ asset('images/default.jpg') }}">
                                                @endif
                                            </div>
                                            @break($index == 5)
                                        @endforeach

                                    </div>
                                @else
                                    <x-wui-badge flat purple label="{{ $item['class_details']->class_category }}" />
                                @endif


                                <div role="status" wire:loading wire:target="openViewAttendees({{ $item['class_details']->id }})">
                                    <svg class="animate-spin size-4 shrink-0"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                <div role="status" wire:loading wire:target="openLeaveClassModal({{ $item['class_roster_id'] }})">
                                    <svg class="animate-spin size-4 shrink-0"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                            @if (!($item['class_details']->class_category == 'individual' && $item['class_roster_details']->proof_of_payment))
                                <x-wui-dropdown>
                                    <x-wui-dropdown.header class="font-semibold" label="Actions">
                                        @if ($item['class_details']->class_category == 'group')
                                            <x-wui-dropdown.item wire:click="openViewAttendees({{ $item['class_details']->id }})"
                                                icon='users' label="View Attendees" />
                                        @endif
                                        @if (!$item['class_roster_details']->proof_of_payment)
                                            <x-wui-dropdown.item wire:click="openLeaveClassModal({{ $item['class_roster_id'] }})"
                                                icon='logout' label="Leave Class" />
                                        @endif
                                    </x-wui-dropdown.header>
                                </x-wui-dropdown>
                            @endif
                        </div>

                        {{-- class description --}}
                        <div class="line-clamp-2">
                            {{ $item['class_details']->class_description }}
                        </div>

                        {{-- buttons --}}
                        @include('livewire.pages.tutee.schedule.indicator-buttons')

                        {{-- date and collapse button --}}
                        <div class="lg:flex flex-wrap lg:flex-nowrap lg:justify-between lg:items-center">
                            <div class="text-[#64748B] inline-flex gap-2 items-center">
                                <x-wui-icon name='calendar' class="size-5" />
                                <p class="font-light text-sm line-clamp-1">
                                    {{ Carbon::parse($item['start_time'])->format('g:i A') }} -
                                    {{ Carbon::parse($item['end_time'])->format('g:i A') }}
                                </p>
                            </div>
                            <div>
                                <div class="flex justify-end mt-2">
                                    <template x-if='expanded == false' x-transition>
                                        <x-wui-button @click="expanded = ! expanded" xs label='View Tutor' icon='arrow-down'
                                            flat />
                                    </template>
                                    <template x-if='expanded == true' x-transition>
                                        <x-wui-button @click="expanded = ! expanded" xs label='Show less' icon='arrow-up'
                                            flat />
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- tutor details --}}
                <div class="text-sm p-3 rounded-md bg-[#F1F5F9]" x-show="expanded" x-collapse x-cloak>
                    <div class="flex flex-wrap gap-3 items-start">
                        @if ($item['tutor']->user->avatar !== null)
                            <img class="border-2 border-[#F1F5F9] overflow-hidden rounded-md size-14"
                                src="{{ Storage::url($item['tutor']->user->avatar) }}">
                        @else
                            <img class="border-2 border-[#F1F5F9] overflow-hidden rounded-md size-14"
                                src="{{ asset('images/default.jpg') }}">
                        @endif
                        <div class="flex flex-col space-y-1">
                            <div class="inline-flex items-center gap-1">
                                <p class="text-sm font-medium">{{ $item['tutor']->user->fname . ' ' . $item['tutor']->user->lname }}</p>
                                <x-wui-icon name='badge-check' class="size-4 text-[#292D32]" solid />
                            </div>
                            <div class="flex flex-col gap-1">
                                <div class="inline-flex items-center gap-1">
                                    <x-wui-icon name='academic-cap' class="size-4 text-[#64748B]" solid />
                                    <span class="text-xs text-[#64748B]">
                                        @php
                                            $degrees = json_decode($item['tutor']->degree, true);
                                        @endphp
                                        {{ is_array($degrees) ? implode(', ', $degrees) : $item['tutor']->degree }}
                                    </span>
                                </div>
                                <div class="inline-flex items-center gap-1">
                                    <x-wui-icon name='at-symbol' class="size-4 text-[#64748B]" solid />
                                    <span class="text-xs text-[#64748B]">
                                        {{ $item['tutor']->user->email }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="mt-2">
                        @if ($item['tutor']->bio == null)
                            This tutor doesn't have a bio yet.
                        @else
                            <strong>Bio:</strong> {{ $item['tutor']->bio }}
                        @endif
                    </p>
                </div>
            </div>

        </div>
    @endif
@empty
    <div>No dates available</div>
@endforelse
