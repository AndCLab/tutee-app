<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public string $role = '';

    public function mount()
    {
        if (Auth::user()->user_type != null) {
            $this->role = Auth::user()->user_type;
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

{{--

#0C3B2E = GREEN
#D9D9D9 = WHITE
#6D9773 = SOFT GREEN

--}}

<div class="hidden md:flex min-w-fit transition-all relative" x-data='sidenav()' x-init='initialize()' x-cloak>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div @class([
        'flex h-screen flex-col sticky top-0 justify-between border-e ',
        'bg-white' => $role == 'tutee',
        'bg-[#0C3B2E]' => $role == 'tutor',
    ])>
        <div class="px-4 py-6">
            <h1 @class([
                'uppercase font-bold text-4xl px-2 font-anton mb-4',
                'text-[#0C3B2E]' => $role == 'tutee',
                'text-[#6D9773]' => $role == 'tutor',
            ]) x-show='!expanded'
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"

            >tutee</h1>

            <ul @class([
                'mt-6 space-y-1',
                'text-[#0C3B2E]' => $role == 'tutee',
                'text-[#D9D9D9]' => $role == 'tutor',
            ])>

                @if ($role == 'tutee')
                    @include('livewire.layout.sidenav_tutee.list')
                @elseif ($role == 'tutor')
                    @include('livewire.layout.sidenav_tutor.list')
                @endif
            </ul>
        </div>

        <div class="sticky inset-x-0 bottom-0 px-4">
            <a href="{{ route('profile') }}" @class([
                'flex items-center gap-2 px-2  py-2 rounded-md w-full',
                'hover:bg-[#F2F2F2]' => $role == 'tutee',
                'hover:bg-[#F2F2F2]/10' => $role == 'tutor',
            ])>
                @if (Auth::user()->avatar == null)
                    <img alt="default.png" src="{{ asset('images/default.jpg') }}"
                        :class="expanded ? 'size-6' : 'size-10' "
                        class="rounded-full object-cover"/>
                @else
                    <img alt="current avatar" src="{{ Storage::url(Auth::user()->avatar) }}"
                        :class="expanded ? 'size-6' : 'size-10' "
                        class="rounded-full object-cover"/>
                @endif
                <div x-show='!expanded'
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90">
                    <p @class([
                        'text-xs max-w-28 truncate',
                        'text-[#0C3B2E]' => $role == 'tutee',
                        'text-[#D9D9D9]' => $role == 'tutor',
                    ])>
                        <strong
                            class="block font-medium max-w-28 truncate">{{ Auth::user()->fname . ' ' . Auth::user()->lname }}</strong>

                        <span>{{ Auth::user()->email }}</span>
                    </p>
                </div>
            </a>

            <!-- Logout -->
            <button wire:click='logout'
                :class="expanded ? 'w-fit' : 'w-full' "
                @class([
                    'inline-flex gap-3 text-sm font-medium px-2 mb-3 py-2 rounded-md',
                    'text-[#0C3B2E] hover:bg-[#F2F2F2]' => $role == 'tutee',
                    'text-[#D9D9D9] hover:bg-[#F2F2F2]/10' => $role == 'tutor',
            ])>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-logout">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                    <path d="M9 12h12l-3 -3" />
                    <path d="M18 15l3 -3" />
                </svg>
                <p x-show='!expanded'
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90">
                    Log Out
                </p>
            </button>
        </div>
    </div>

    {{-- Collapse button --}}
    <div class="absolute h-full left-full">
        <div class="sticky top-0 flex h-screen justify-center items-center">
            <button @click='toggleSidenav' class="text-[#0C3B2E]">
                <div x-if='!expanded'>
                    <svg x-show='!expanded' xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div x-if='expanded`'>
                    <svg x-show='expanded' xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </div>
    </div>
    <script>
        function sidenav() {
            return {
                expanded: false,
                initialize() {
                    this.expanded = JSON.parse(localStorage.getItem('sidenavOpen')) ?? false;
                },
                toggleSidenav() {
                    this.expanded = !this.expanded;
                    localStorage.setItem('sidenavOpen', JSON.stringify(this.expanded));
                }
            }
        }
    </script>
</div>

