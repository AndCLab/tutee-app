<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect(route('login.admin'), navigate: true);
    }
}; ?>

{{--

#0C3B2E = GREEN
#D9D9D9 = WHITE
#6D9773 = SOFT GREEN

--}}

<div class="hidden sm:flex min-w-fit transition-all relative" x-data="sidenav" x-init='initialize' x-cloak>

    {{-- sidenav container --}}
    <div class="flex h-screen flex-col sticky top-0 justify-between border-e bg-[#0F172A] w-64">
        <div class="px-4 py-6">

            {{-- temporary Logo --}}
            <h1 class="uppercase font-bold text-center text-4xl px-2 font-anton mb-4 text-[#6D9773]" x-show='expanded'>t</h1>

            {{-- tutee header --}}
            <h1 class="uppercase font-bold text-4xl px-2 font-anton mb-4 text-[#6D9773]" x-show='!expanded'>tutee</h1>

            <div class="w-full h-[0.063rem] bg-[#8F8F8F]"
            ></div>

            {{-- tutee and tutor list --}}
            <ul class="mt-6 space-y-1 text-[#D9D9D9]">
                @include('livewire.layout.sidenav_admin.list')
            </ul>
        </div>

        {{-- profile and logout --}}
        <div class="px-4">
            <!-- Logout -->
            <div x-data="{ tooltip: false }" class="relative">
                <button wire:click='logout' :class="expanded ? 'w-fit' : 'w-full'" class="inline-flex gap-3 text-sm font-medium px-2 mb-3 py-2 rounded-md text-[#D9D9D9] hover:text-[#6D9773]"
                    x-on:mouseenter="tooltip = !tooltip" x-on:mouseleave="tooltip = !tooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logout">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                        <path d="M9 12h12l-3 -3" />
                        <path d="M18 15l3 -3" />
                    </svg>
                    <p x-show='!expanded'>
                        Log Out
                    </p>
                </button>
                <div x-show="tooltip"
                    class="z-50 text-sm absolute top-0 left-full bg-white border-2 rounded-md py-1 px-2 ml-1 mt-1 text-nowrap"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                    Log Out
                </div>
            </div>
        </div>
    </div>

    {{-- collapse button --}}
    <div class="absolute h-full left-full">
        <div class="sticky top-0 flex h-screen justify-center items-center">
            <button @click='toggleSidenav' class="text-[#0F172A]">
                <template x-if='!expanded'>
                    <svg x-show='!expanded' xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </template>
                <template x-if='expanded'>
                    <svg x-show='expanded' xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </template>
            </button>
        </div>
    </div>

</div>

{{-- script for collapse sidenav --}}
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
