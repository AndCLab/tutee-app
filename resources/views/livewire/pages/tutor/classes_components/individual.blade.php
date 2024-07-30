<section>
    <form wire:submit='createIndividualClass'>
        <div class="space-y-3">
            {{-- class name --}}
            <div>
                <x-wui-input wire:model='class_name' label="Class Name" placeholder='Enter class name' shadowless/>
            </div>

            {{-- class description --}}
            <div>
                <x-wui-textarea wire:model='class_description' label="Class Description" class="resize-none" placeholder='Enter class description' shadowless/>
            </div>

            {{-- class schedule --}}
            <div>
                <div class="flex flex-col justify-between items-start mb-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Class Schedule
                    </label>
                    <button
                        type='button'
                        class="text-secondary-400 text-start border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none"
                        wire:click="$set('showClassSchedule', true)"
                    >
                        @if ($sched_start_date && $sched_end_date)
                            <span class="text-black">
                                Filled
                            </span>
                        @else
                            Class Schedule
                        @endif
                    </button>
                </div>
            </div>

            {{-- class fields --}}
            <div>
                <x-wui-select
                    wire:model="class_fields"
                    label="Class Fields"
                    placeholder="Select fields"
                    multiselect
                    shadowless
                >
                    @foreach ($getFields as $field)
                        <x-wui-select.option
                            label="{{ $field['field_name'] }}"
                            value="{{ $field['field_name'] }}"
                        />
                    @endforeach
                </x-wui-select>
            </div>

            {{-- class price --}}
            <div x-data="{ open: false }">
                <div class="mb-1">
                    <x-wui-toggle left-label="Class Price" @click="open = ! open" wire:model='IndiClassFeeToggle'/>
                </div>
                <div x-show='open' x-cloak x-transition>
                    <x-wui-inputs.currency wire:model='class_fee' icon="cash" placeholder="Enter class price" shadowless/>
                </div>
            </div>

            {{-- Virtual or Physical Class --}}
            <div class="flex flex-col gap-4" x-data="{ tab: window.location.hash ? window.location.hash : '#virtual' }">
                {{-- Left panel --}}
                <ul class="flex bg-[#F1F5F9] px-1.5 py-1.5 gap-2 rounded-lg">
                    <li class="w-full text-center">
                        <a :class="tab !== '#virtual' ? '' : 'bg-white'"
                            class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out"
                            x-on:click.prevent="tab='#virtual'"> Virtual Class </a>
                    </li>
                    <li class="w-full">
                        <a :class="tab !== '#physical' ? '' : 'bg-white'"
                            class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out"
                            x-on:click.prevent="tab='#physical'"> Physical Class </a>
                    </li>
                </ul>

                {{-- Right panel --}}
                <div>
                    <div x-show="tab == '#virtual'" x-cloak>
                        <div class="max-w-xl">
                            <x-wui-input wire:model='class_link' label="Generated Link" placeholder="your name">
                                <x-slot name="append">
                                    <div class="absolute inset-y-0 right-0 flex items-center p-0.5">
                                        <x-wui-button
                                            class="h-full rounded-r-md"
                                            icon="clipboard-copy"
                                            primary
                                            flat
                                            squared
                                        />
                                    </div>
                                </x-slot>
                            </x-wui-input>
                        </div>
                    </div>

                    <div x-show="tab == '#physical'" x-cloak>
                        <div class="max-w-xl">
                            <x-wui-input wire:model='class_location' label="Class Venue" placeholder='Enter class venue' shadowless/>
                        </div>
                    </div>
                </div>
            </div>

            {{-- submit button --}}
            <x-primary-button class="w-full" wireTarget='createIndividualClass'>
                Create Class
            </x-primary-button>
        </div>
    </form>
</section>
