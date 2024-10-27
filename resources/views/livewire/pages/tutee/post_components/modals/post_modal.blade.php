<!-- Post modal class -->
<x-wui-modal.card wire:model="showPostModal" title='Post Something' align='center' max-width='xl'>
    <div class="flex items-start space-x-3 mb-4">
        <img alt="User Avatar"
            src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('images/default.jpg') }}"
            class="w-10 h-10 rounded-full object-cover border border-[#F1F5F9] overflow-hidden" />
        <p class="font-medium">{{ Auth::user()->fname . ' ' . Auth::user()->lname }}</p>
    </div>

    {{-- post desc --}}
    <div class="mb-4">
        <x-wui-textarea autofocus wire:model="post_desc" placeholder="Enter post description" shadowless/>
    </div>

    <div class="flex space-x-3 mb-4">
        {{-- class fields --}}
        <x-wui-select wire:model="class_fields" placeholder="Select fields" multiselect shadowless>
            @foreach ($getFields as $field)
                <x-wui-select.option label="{{ $field['field_name'] }}"
                    value="{{ $field['field_name'] }}" />
            @endforeach
        </x-wui-select>

        {{-- class schedule --}}
        <x-wui-datetime-picker placeholder="Select Date" wire:model.live="class_date"
            parse-format="YYYY-MM-DD HH:mm" display-format='dddd, MMMM D, YYYY' :min="now()"
            without-time shadowless />
    </div>

    <div class="flex space-x-3 mb-4">
        {{-- Class Fee --}}
        <div x-data="{ open: false }" class="whitespace-nowrap">
            <div class="mb-1">
                <x-wui-toggle class="text-nowrap" left-label="Class Price" @click="open = ! open" wire:model='EstimatedPriceToggle'/>
            </div>
            <div x-show='open' x-cloak x-transition>
                <x-wui-inputs.currency wire:model='class_fee' icon="cash" placeholder="Estimated Price" shadowless/>
            </div>
        </div>

        {{-- Class Category --}}
        <x-wui-select wire:model="class_category" label='Class Category' placeholder="Select Category" shadowless>
            <x-wui-select.option label="Individual" value="individual" />
            <x-wui-select.option label="Group" value="group" />
        </x-wui-select>

    </div>


    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-400 mr-2">
        Class Type
    </label>
    {{-- Virtual or Physical Class --}}
    <div class="flex flex-col gap-4" x-data="{ classType: '{{ $class_type }}' }">
        {{-- Radio buttons with original tab styling --}}
        <ul class="flex bg-[#F1F5F9] px-1.5 py-1.5 gap-2 rounded-lg">
            <li class="w-full text-center">
                <label :class="classType !== 'virtual' ? '' : 'bg-white'"
                    class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out">
                    <input type="radio" wire:model.defer='class_type' id="virtual" name="virtual" x-model="classType" value="virtual" class="hidden" />
                    Virtual Class
                </label>
            </li>
            <li class="w-full text-center">
                <label :class="classType !== 'physical' ? '' : 'bg-white'"
                    class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out">
                    <input type="radio" wire:model.defer='class_type' id="physical" name="physical" x-model="classType" value="physical" class="hidden" />
                    Physical Class
                </label>
            </li>
        </ul>

        {{-- Conditional inputs --}}
        <div>
            <div x-show="classType === 'physical'" x-cloak>
                <div class="w-full">
                    <x-wui-input
                        wire:model='class_location'
                        label="Class Venue"
                        placeholder='Enter class venue'
                        shadowless
                        x-init="$watch('classType', value => {
                            if (value === 'virtual') {
                                $wire.set('class_location', null);
                            }
                        })"/>
                </div>
            </div>
        </div>
    </div>


    <x-slot name='footer'>
        <div class="grid grid-cols-2 gap-2">
            <x-secondary-button x-on:click='close'>
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ms-3" wireTarget="post" wire:click.prevent='post'>
                {{ __('Post') }}
            </x-primary-button>
        </div>
    </x-slot>
</x-wui-modal.card>

