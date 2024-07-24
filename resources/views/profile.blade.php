<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-10 sm:px-6 lg:px-8 space-y-6">
            {{-- Sets the default tab --}}
            <div class="flex flex-col md:flex-row gap-4" x-data="{ tab: window.location.hash ? window.location.hash : '#account' }">

                {{-- Mobile-based left panel --}}
                <ul class="flex md:hidden bg-[#F1F5F9] px-1.5 py-1.5 gap-2 rounded-lg">
                    <li class="w-full text-center">
                        <a :class="tab !== '#account' ? 'hover:bg-[#F2F2F2]' : 'bg-white'"
                            class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out"
                            x-on:click.prevent="tab='#account'"> Account </a>
                    </li>
                    <li class="w-full">
                        <a :class="tab !== '#password' ? 'hover:bg-[#F2F2F2]' : 'bg-white'"
                            class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out"
                            x-on:click.prevent="tab='#password'"> Password </a>
                    </li>
                    <li class="w-full">
                        <a :class="tab !== '#interests' ? 'hover:bg-[#F2F2F2]' : 'bg-white'"
                            class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out"
                            x-on:click.prevent="tab='#interests'"> Interests </a>
                    </li>
                    <li class="w-full">
                        <a :class="tab !== '#log-sessions' ? 'hover:bg-[#F2F2F2]' : 'bg-white'"
                            class="inline-flex w-full cursor-pointer text-nowrap justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out"
                            x-on:click.prevent="tab='#log-sessions'"> Log Sessions </a>
                    </li>
                </ul>

                {{-- Web-based left panel --}}
                <ul class="md:flex flex-col hidden">
                    <li class="flex items-center gap-1">
                        <div :class="tab === '#account' ? 'bg-[#0C3B2E]' : 'bg-transparent'" class="h-5 w-1 rounded-full"></div>
                        <a :class="tab !== '#account' ? 'hover:bg-[#F2F2F2]' : 'cursor-auto'"
                            class="inline-flex w-full cursor-pointer items-center gap-3 rounded-md px-2 pr-10 py-1.5 text-sm font-semibold transition-all ease-in-out"
                            x-on:click.prevent="tab='#account'"> Account </a>
                    </li>
                    <li class="flex items-center gap-1">
                        <div :class="tab === '#password' ? 'bg-[#0C3B2E]' : 'bg-transparent'" class="h-5 w-1 rounded-full"></div>
                        <a :class="tab !== '#password' ? 'hover:bg-[#F2F2F2]' : 'cursor-auto'"
                            class="inline-flex w-full cursor-pointer items-center gap-3 rounded-md px-2 pr-10 py-1.5 text-sm font-semibold transition-all ease-in-out"
                            x-on:click.prevent="tab='#password'"> Password </a>
                    </li>
                    <li class="flex items-center gap-1">
                        <div :class="tab === '#interests' ? 'bg-[#0C3B2E]' : 'bg-transparent'" class="h-5 w-1 rounded-full"></div>
                        <a :class="tab !== '#interests' ? 'hover:bg-[#F2F2F2]' : 'cursor-auto'"
                            class="inline-flex w-full cursor-pointer items-center gap-3 rounded-md px-2 pr-10 py-1.5 text-sm font-semibold transition-all ease-in-out"
                            x-on:click.prevent="tab='#interests'"> Interests </a>
                    </li>
                    <li class="flex items-center gap-1">
                        <div :class="tab === '#log-sessions' ? 'bg-[#0C3B2E]' : 'bg-transparent'" class="h-5 w-1 rounded-full"></div>
                        <a :class="tab !== '#log-sessions' ? 'hover:bg-[#F2F2F2]' : 'cursor-auto'"
                            class="inline-flex text-nowrap w-full cursor-pointer items-center gap-3 rounded-md px-2 pr-10 py-1.5 text-sm font-semibold transition-all ease-in-out"
                            x-on:click.prevent="tab='#log-sessions'"> Log Sessions </a>
                    </li>
                </ul>

                {{-- Divider --}}
                <div class="max-h-fit w-[0.063rem] hidden md:block rounded-md bg-[#D9D9D9]"></div>

                {{-- Right panel --}}
                <div>
                    <div x-show="tab == '#account'" x-cloak>
                        <div class="max-w-xl">
                            <livewire:profile.update-profile-information-form />
                        </div>
                    </div>

                    <div x-show="tab == '#password'" x-cloak>
                        <div class="max-w-xl">
                            <livewire:profile.update-password-form />
                        </div>
                    </div>

                    <div x-show="tab == '#interests'" x-cloak>
                        <div class="max-w-xl">
                            <livewire:profile.interest-user-form />
                        </div>
                    </div>

                    <div x-show="tab == '#log-sessions'" x-cloak>
                        <div class="max-w-xl">
                            <livewire:profile.browser-sessions />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
