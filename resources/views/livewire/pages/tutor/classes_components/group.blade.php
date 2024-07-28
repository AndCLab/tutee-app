<section>
    <div class="space-y-3">
        <div>
            <x-wui-input wire:model='class_name' label="Class Name" placeholder='Enter class name' shadowless/>
        </div>
        <div>
            <x-wui-textarea wire:model='class_description' label="Class Description" class="resize-none" placeholder='Enter class description' shadowless/>
        </div>
        <div>
            <div class="flex flex-col justify-between items-start mb-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Class Registration Date
                </label>
                <button
                    type='button'
                    class="text-secondary-400 text-start border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none"
                    wire:click="$set('showRegistrationDate', true)"
                >
                @if ($regi_start_date && $regi_end_date)
                    <span class="text-black">
                        Filled
                    </span>
                @else
                    Class Registration Date
                @endif
                </button>
            </div>
        </div>
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
        <div>
            <x-wui-select
                wire:model='fields'
                label="Class Fields"
                placeholder="Select fields"
                multiselect
                :options="['Active', 'Pending', 'Stuck', 'Done']"
                shadowless
            />
        </div>
        <div x-data="{ open: false }">
            <div class="mb-1">
                <x-wui-toggle left-label="Class Price" @click="open = ! open" wire:model='GroupClassFeeToggle'/>
            </div>
            <div x-show='open' x-cloak x-transition>
                <x-wui-inputs.currency wire:model='class_fee' placeholder="Enter class price" shadowless/>
            </div>
        </div>
    </div>
</section>
