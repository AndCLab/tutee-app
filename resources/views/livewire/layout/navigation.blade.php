<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;
use App\Models\Tutor;

new class extends Component {
    public string $role = '';
    public $user;
    public $tutor;

    public function mount()
    {
        $this->user = Auth::user();
        if ($this->user->user_type != null) {
            $this->role = $this->user->user_type;
        }
        $this->tutor = Tutor::where('user_id', Auth::id())->first();
    }

    // Testing purposes
    public function switchRole(){
        if($this->role == 'tutee'){
            $this->user->user_type = 'tutor';
            $this->user->save();

            $this->redirectIntended(default: route('tutor.discover', absolute: false), navigate: true);

        } else{
            $this->user->user_type = 'tutee';
            $this->user->save();

            $this->redirectIntended(default: route('tutee.discover', absolute: false), navigate: true);
        }
    }

    public function beATutee(){
        if($this->user->user_type == 'tutor' && $this->user->is_applied == 0) {
            $this->user->is_applied = 1;
            $this->user->save();
            return redirect()->route('stepper.be-a-tutee');
        }
    }

    public function applyAsTutor(){
        if($this->user->user_type == 'tutee' && $this->user->is_applied == 0) {
            $this->user->is_applied = 1;
            $this->user->save();
            return redirect()->route('stepper.apply-as-tutor');
        }
    }

    public function verifyTutor(){
        if ($this->tutor) {
            $this->tutor->verify_status = 'pending';
            $this->tutor->save();
        }
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/login', navigate: true);
    }
}; ?>

{{-- drop-shadow-md --}}
<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-border/40 bg-background/95 backdrop-blur">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between min-h-fit py-3">
            <div></div>
            <div class="hidden sm:flex sm:gap-2 sm:items-center sm:ms-6">
                {{-- Be a Tutee (Tutor and not yet Tutee) --}}
                @if ($role == 'tutor' && !$user->is_applied)
                    @if ($tutor->verify_status === 'not_verified')
                        <x-wui-button sm wire:click='verifyTutor' flat primary icon='badge-check'
                        spinner='verifyTutor' label='Verify Tutor Account' />
                    @elseif ($tutor->verify_status === 'pending')
                        <p>Verification pending.</p>
                    @else
                        <p>Your Tutor account has been verified.</p>
                    @endif

                    <div>
                        <livewire:role-icon />

                        <x-wui-button sm wire:click='beATutee' flat primary icon='switch-vertical'
                        spinner='beATutee' label='Be a Tutee' /> 
                        @include('livewire.layout.topnav_tutor.menu')                      
                    </div>

                    
                    

                {{-- Switch to Tutee (Tutee and applied as Tutor) --}}
                @elseif ($role == 'tutor' && $user->is_applied)
                    @if ($tutor->verify_status === 'not_verified')
                        <x-wui-button sm wire:click='verifyTutor' flat primary icon='badge-check'
                        spinner='verifyTutor' label='Verify Tutor Account' />
                    @elseif ($tutor->verify_status === 'pending')
                        <p>Verification pending.</p>
                    @else
                        <p>Your Tutor account has been verified.</p>
                    @endif

                    <div>
                        <livewire:role-icon />

                        <x-wui-button sm wire:click='switchRole' flat primary icon='switch-vertical'
                        spinner='switchRole' label='Switch to Tutee' />
                        @include('livewire.layout.topnav_tutor.menu')    
                    </div>

                {{-- Apply as Tutor (Tutee and not yet Tutor) --}}
                @elseif ($role == 'tutee' && !$user->is_applied)

                    <div>
                        <livewire:role-icon />

                        <x-wui-button sm wire:click='applyAsTutor' flat primary icon='switch-vertical'
                        spinner='applyAsTutor' label='Apply as Tutor' />
                        @include('livewire.layout.topnav_tutee.menu')
                    </div>


                {{-- Switch to Tutor (Tutor and applied as Tutee) --}}
                @elseif ($role == 'tutee' && $user->is_applied)
                    <div>
                        <livewire:role-icon />

                        <x-wui-button sm wire:click='switchRole' flat primary icon='switch-vertical'
                        spinner='switchRole' label='Switch to Tutor' />
                        @include('livewire.layout.topnav_tutee.menu')
                    </div>

                @endif

                {{-- Switch role testing purposes
                <x-wui-button sm wire:click='switchRole' flat primary icon='switch-vertical' spinner='switchRole' label='switch role testing kay kapuy logout :)' />
                --}}
            </div>

            <!-- Settings Dropdown -->
            {{-- <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div> --}}

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @php
        $route = Auth::user()->user_type == 'tutee' ? 'tutee.discover' : 'tutor.discover';
    @endphp

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route($route)" :active="request()->routeIs($route)" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
