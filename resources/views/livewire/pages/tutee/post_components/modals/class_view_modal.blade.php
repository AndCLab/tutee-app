@php
    use Carbon\Carbon;
@endphp

@if ($getClass)
    <x-wui-modal.card wire:model="showClassModal" class="space-y-3" align='center' max-width='xl'>
        <div class="flex gap-2 items-start">
            <div class="size-16">
                <img
                    alt="User Avatar"
                    src="{{ $getClass->tutor->user->avatar ? Storage::url($getClass->tutor->user->avatar) : asset('images/default.jpg') }}"
                    class="rounded-full object-cover border border-[#F1F5F9] overflow-hidden"
                />
            </div>
            <div class="w-full space-y-2">
                <p class="flex gap-2 font-semibold">
                    {{ $getClass->tutor->user->fname .' '. $getClass->tutor->user->lname}}
                </p>
                <div class="inline-flex items-center gap-1">
                    <x-wui-icon name='academic-cap' class="size-4 text-[#64748B]" solid />
                    <p class="font-light text-xs">
                        {{ implode(', ', json_decode($getClass->tutor->degree, true)) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- class name --}}
        <div class="flex flex-col font-semibold">
            Class Name
            <span class="font-light">
                {{ $getClass->class_name}}
            </span>
        </div>

        {{-- class description --}}
        <div class="flex flex-col font-semibold">
            <div class="flex gap-2 items-center">
                About the Class
                <div>
                    @if ( $getClass->class_category == 'group')
                        <x-wui-badge flat warning label="{{ $getClass->class_category }}" />
                    @else
                        <x-wui-badge flat purple label="{{ $getClass->class_category }}" />
                    @endif
                </div>
            </div>
            <span class="font-light">
                {{ $getClass->class_description}}
            </span>
        </div>

        {{-- collapsable class details --}}
        <div class="p-3 py-2 rounded-md bg-[#E1E7EC]" x-data="{ expanded: false }">
            <div class="flex cursor-pointer justify-between items-center" @click="expanded = ! expanded">
                <span class="font-semibold text-sm">Join Class</span>
                <template x-if='expanded == false' x-transition>
                    <x-wui-button xs label='View more' icon='arrow-down'
                        flat />
                </template>
                <template x-if='expanded == true' x-transition>
                    <x-wui-button xs label='View less' icon='arrow-up'
                        flat />
                </template>
            </div>
            <div class="text-sm" x-show="expanded" x-collapse x-cloak>
                <p>
                    <strong>Class Status:</strong> {{ $getClass->class_status == 1 ? 'Open' : 'Closed' }}
                </p>
                <p>
                    <strong>Class Type:</strong> {{ ucfirst($getClass->class_type) }}
                </p>
                <p>
                    <strong>Class Fee:</strong> {{ $getClass->class_fee == 0.0 ? 'Free Class' : number_format($getClass->class_fee, 2) }}
                </p>
                @if ($getClass->class_students >= 2)
                    <p>
                        There are {{ $getClass->class_students }} slots available
                    </p>
                @endif
                <p>
                    <strong>Class Fields:</strong>
                    {{ implode(', ', json_decode($getClass->class_fields, true)) }}
                </p>
                <p>
                    <strong>Class Location:</strong> {{ $getClass->class_location }}
                </p>
                <p>
                    <strong>Upcoming Schedule Date:</strong>
                    @foreach ($getClass->schedule->recurring_schedule as $recurring)
                        @if (Carbon::parse($recurring->dates)->isToday() || Carbon::parse($recurring->dates)->isFuture())
                            <span>
                                {{ Carbon::parse($recurring->dates)->format('l jS \\of F Y') }}
                            </span>
                            @break
                        @endif
                    @endforeach
                </p>
                <p>
                    <strong>Time:</strong>
                    {{ Carbon::create($getClass->schedule->start_time)->format('g:i A') }} -
                    {{ Carbon::create($getClass->schedule->end_time)->format('g:i A') }}
                </p>
                <x-primary-button class="w-full mt-5" wire:click='joinClass' wireTarget='joinClass'>
                    Join Class
                </x-primary-button>
            </div>
        </div>

        <x-slot name='footer'>
            <x-tertiary-button class="w-full inline-flex gap-2 items-center justify-center">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"
                    fill="#292D32"  class="icon icon-tabler icons-tabler-filled icon-tabler-message">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M18 3a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-4.724l-4.762 2.857a1 1 0 0 1 -1.508 -.743l-.006 -.114v-2h-1a4 4 0 0 1 -3.995 -3.8l-.005 -.2v-8a4 4 0 0 1 4 -4zm-4 9h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m2 -4h-8a1 1 0 1 0 0 2h8a1 1 0 0 0 0 -2" />
                </svg>
                <p>
                    Message {{ $getClass->tutor->user->fname }}
                </p>
            </x-tertiary-button>
            <div class="flex items-center justify-between font-light text-xs pt-2">
                <div class="flex gap-2 items-center text-[#64748B]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p>Posted on {{ $getClass->created_at->format('l, F d Y g:i A') }}</p>
                </div>
                <x-wui-button wire:click='reportClassModal({{ $getClass->id }})' spinner='reportClassModal({{ $getClass->id }})' label="Report Content" flat xs icon="exclamation" />
            </div>
        </x-slot>
    </x-wui-modal.card>
@endif
