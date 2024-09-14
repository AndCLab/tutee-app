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
                <x-wui-button label="Class Schedule"
                    flat
                    :negative="$errors->any()"
                    :emerald="!$errors->any()"
                    xs
                    icon="calendar"
                    wire:click="$set('showClassSchedule', true)"
                />
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
                    <li class="w-full text-center">
                        <a :class="tab !== '#physical' ? '' : 'bg-white'"
                            class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out"
                            x-on:click.prevent="tab='#physical'"> Physical Class </a>
                    </li>
                </ul>

                {{-- Right panel --}}
                <div>
                    <div x-show="tab == '#virtual'" x-cloak>
                        <div class="max-w-xl">
                            <x-wui-input wire:model='class_link' label="Virtual Session" placeholder="Enter virtual link" shadowless/>
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
