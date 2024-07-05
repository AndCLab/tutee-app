<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    // class properties

    // states
    public $showModal;

}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 p-6">
        <div class="lg:grid lg:grid-cols-3 items-start gap-5">

            {{-- Class List --}}
            <div class="lg:col-span-2 space-y-3">
                <p class="capitalize font-semibold text-xl mb-9">class list</p>

                {{-- Class List: Search and Filter --}}
                <div class="flex gap-2">
                    <div class="w-full">
                        <x-wui-input placeholder='Search class...' icon='search' />
                    </div>
                    <div class="w-fit">
                        <x-wui-select placeholder="Sort by">
                            <x-wui-select.option label="Ascending" value="1" />
                            <x-wui-select.option label="Descending" value="2" />
                        </x-wui-select>
                    </div>
                    <x-wui-dropdown>
                        <x-slot name="trigger">
                            <x-wui-button.circle flat md squared icon='adjustments' />
                        </x-slot>

                        <x-wui-dropdown.item label="View all classes" />
                        <x-wui-dropdown.item label="View pending classes" />
                    </x-wui-dropdown>
                </div>

                {{-- Class List: Class Cards --}}
                <div class="space-y-3">
                    @for ($i = 0; $i < 10; $i++)
                        <div class="w-full bg-[#F1F5F9] p-4 rounded-md text-[#0F172A] space-y-4">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center">
                                    <p class="font-semibold">Data Structures and Algorithms</p>
                                    <x-wui-dropdown>
                                        <x-wui-dropdown.header class="font-semibold" label="Actions">
                                            <x-wui-dropdown.item icon='eye' label="Inspect" />
                                            <x-wui-dropdown.item icon='pencil-alt' label="Edit" />
                                            <x-wui-dropdown.item icon='trash' label="Withdraw" />
                                        </x-wui-dropdown.header>
                                    </x-wui-dropdown>
                                </div>
                                <div class="flex gap-2 items-center text-[#64748B] text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p>Class starts on December 2, 2029</p>
                                </div>
                            </div>
                            <p class="line-clamp-3 antialiased leading-snug">
                                Are you interested in enhancing your proficiency in data structures and algorithms to fortify
                                your problem-solving skills and elevate your programming expertise? Delving into this
                                foundational aspect of computer science is crucial for optimizing code efficiency, enabling
                                seamless problem-solving, and contributing to the creation of robust software solutions.
                            </p>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Create Class --}}
            <div class="space-y-3 sticky top-[5rem]">
                <p class="capitalize font-semibold text-xl mb-9">create class</p>
                <div class="space-y-3">
                    <div>
                        <x-wui-input label="Class Name" placeholder='Enter class name'/>
                    </div>
                    <div>
                        <x-wui-textarea label="Class Description" class="resize-none" placeholder='Enter class description'/>
                    </div>
                    <div>
                        <div class="flex flex-col justify-between items-start mb-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Class Schedule
                            </label>
                            <button
                                type='button'
                                class="text-secondary-400 text-start border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm"
                                wire:click="$set('showModal', true)"
                            >
                            Class Schedule
                            </button>
                        </div>
                    </div>
                    <div>
                        <x-wui-select
                            label="Class Fields"
                            placeholder="Select fields"
                            multiselect
                            :options="['Active', 'Pending', 'Stuck', 'Done']"
                        />
                    </div>
                    <div x-data="{ open: false }">
                        <div class="mb-1">
                            <x-wui-toggle left-label="Class Price" @click="open = ! open" wire:model='model'/>
                        </div>
                        <div x-show='open' x-cloak x-transition>
                            <x-wui-inputs.currency placeholder="Enter class price" wire:model="class_price" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create class modal --}}
    <x-wui-modal wire:model="showModal" max-width='md' persistent>
        <x-wui-card title='Class Schedule'>
            <div class="grid grid-cols-1 gap-4">
                <x-wui-datetime-picker
                    label="Start Schedule Time"
                    placeholder="January 1, 2000"
                    wire:model="start_date"
                />
                <x-wui-datetime-picker
                    label="End Schedule Time"
                    placeholder="December 1, 2000"
                    wire:model="end_date"
                />
            </div>
            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-wui-button primary label="Done" spinner='showModal' x-on:click='close' />
                </div>
            </x-slot>
        </x-wui-card>
    </x-wui-modal>
</section>
