<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-6">
        <div class="md:grid md:grid-row items-start gap-5 pb-3">
            <p class="capitalize font-semibold text-xl">Interests</p>
        </div>

        <div class="flex items-center space-x-3">
            <img
                alt="User Avatar"
                src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('images/default.jpg') }}"
                class="w-10 h-10 rounded-full object-cover border border-[#F1F5F9] overflow-hidden"
            />
            <div onclick="$openModal('postModal')" class="cursor-pointer border border-gray-300 bg-white rounded-md px-4 py-2 text-gray-700 hover:bg-gray-50 mx-4 max-w-xs">
                What do you want to learn?
            </div>
        </div>

        <!-- Post modal class -->
        <x-wui-modal name="postModal" align='center' max-width='xl' persistent>
            <x-wui-card>
                <h2 class="text-lg font-medium text-gray-900 flex space-x-4 mb-4">
                    Post Something
                </h2>

                <div class="flex items-center space-x-3 mb-4">
                    <img
                        alt="User Avatar"
                        src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('images/default.jpg') }}"
                        class="w-10 h-10 rounded-full object-cover border border-[#F1F5F9] overflow-hidden"
                    />
                    <strong class="block font-medium max-w-28 truncate">{{ Auth::user()->fname }}</strong>
                </div>

                <div class="flex space-x-4 mb-4">
                    {{-- class fields --}}
                    <x-wui-select
                        wire:model="class_fields"
                        placeholder="Class fields"
                        multiselect
                        shadowless
                    >
                    </x-wui-select>

                    {{-- class schedule --}}
                    <x-wui-select
                        wire:model="class_schedule"
                        placeholder="Class schedule"
                        multiselect
                        shadowless
                    >
                    </x-wui-select>
                </div>

                <div class="flex space-x-4 mb-4">
                    <x-wui-inputs.currency wire:model.live.debounce.250ms='pricing' icon="cash" placeholder="Pricing" shadowless />
                </div>

                Class Type
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

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click='close'>
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-primary-button class="ms-3" wire:click="post" x-bind:disabled="!open">
                        {{ __('Post') }}
                    </x-primary-button>
                </div>
            </x-wui-card>
        </x-wui-modal>
    </div>
</section>
