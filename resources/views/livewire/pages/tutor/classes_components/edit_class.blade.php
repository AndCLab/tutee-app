<section>
    <form wire:submit='editClass'>
        <div class="space-y-3">
            {{-- class schedule --}}
            <div class="grid grid-cols-2 gap-4">
                @if ($sched_start_date && $sched_end_date)
                    <x-wui-datetime-picker
                        label="Schhedule Start"
                        placeholder="January 1, 2000"
                        wire:model.live="sched_start_date"
                        parse-format="YYYY-MM-DD HH:mm"
                        display-format='dddd, MMMM D, YYYY h:mm A'
                        :min="now()"
                        shadowless
                    />
                    <x-wui-datetime-picker
                        label="Registration End"
                        placeholder="December 1, 2000"
                        wire:model.blur="sched_end_date"
                        parse-format="YYYY-MM-DD HH:mm"
                        display-format='dddd, MMMM D, YYYY h:mm A'
                        :min="now()"
                        shadowless
                    />
                @endif
            </div>

            {{-- class registration --}}
            @if ($class_category == 'group')
                <div class="grid grid-cols-2 gap-4">
                    @if ($regi_start_date != null && $regi_end_date != null)
                        <x-wui-datetime-picker
                            label="Registration Start"
                            placeholder="January 1, 2000"
                            wire:model.live="regi_start_date"
                            parse-format="YYYY-MM-DD HH:mm"
                            display-format='dddd, MMMM D, YYYY h:mm A'
                            :min="now()"
                            shadowless
                        />
                        <x-wui-datetime-picker
                            label="Registration End"
                            placeholder="December 1, 2000"
                            wire:model.blur="regi_end_date"
                            parse-format="YYYY-MM-DD HH:mm"
                            display-format='dddd, MMMM D, YYYY h:mm A'
                            :min="now()"
                            shadowless
                            />
                    @endif
                </div>
            @endif

            {{-- class name --}}
            <div>
                <x-wui-input wire:model='class_name' label="Class Name" placeholder='Enter class name' shadowless/>
            </div>

            {{-- class description --}}
            <div>
                <x-wui-textarea wire:model='class_description' label="Class Description" class="resize-none" placeholder='Enter class description' shadowless/>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- class fields --}}
                <div>
                    <x-wui-select
                        wire:model="class_fields"
                        label="Class Fields"
                        placeholder="Select fields"
                        multiselect
                        shadowless
                    >
                        {{-- @foreach ($class_fields as $field)
                            <x-wui-select.option
                                label="{{ $field }}"
                                value="{{ $field }}"
                            />
                        @endforeach --}}
                        @foreach ($getFields as $field)
                            <x-wui-select.option
                                label="{{ $field['field_name'] }}"
                                value="{{ $field['field_name'] }}"
                            />
                        @endforeach
                    </x-wui-select>
                </div>

                {{-- class price --}}
                <div>
                    <x-wui-inputs.currency wire:model='class_fee' label='Class Fee' icon="cash" placeholder="Enter class price" shadowless/>
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
                        <div class="w-full">
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
                        <div class="w-full">
                            <x-wui-input wire:model='class_location' label="Class Venue" placeholder='Enter class venue' shadowless/>
                        </div>
                    </div>
                </div>
            </div>

            {{-- submit button --}}
            <div class="grid grid-cols-2 gap-4">
                <x-secondary-button type='submit' wireTarget='editClass' class="w-full">
                    Update Class
                </x-secondary-button>
                <x-wui-button label='Cancel' flat wire:click="resetModalState" spinner='resetModalState'/>
            </div>
        </div>
    </form>
</section>
