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

        $this->redirect('/', navigate: true);
    }
}; ?>

{{--

#0C3B2E = GREEN
#D9D9D9 = WHITE
#6D9773 = SOFT GREEN

--}}

<div @class([
    'hidden sm:flex sticky top-0 h-screen flex-col justify-between border-e min-w-fit',
    'bg-white' => $role == 'tutee',
    'bg-[#0C3B2E]' => $role == 'tutor',
])>
    <div class="px-4 py-6">
        <h1 @class([
            'uppercase font-bold text-4xl font-anton mb-4',
            'text-[#0C3B2E]' => $role == 'tutee',
            'text-[#6D9773]' => $role == 'tutor',
        ])>tutee</h1>

        <ul @class([
            'mt-6 space-y-3',
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

    <div class="sticky inset-x-0 bottom-0">
        <a href="{{ route('profile') }}" wire:navigate class="flex items-center gap-2 px-4">
            <img alt=""
                src="https://images.unsplash.com/photo-1600486913747-55e5470d6f40?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1770&q=80"
                class="size-10 rounded-full object-cover" />
            <div>
                <p @class([
                    'text-xs',
                    'text-[#0C3B2E]' => $role == 'tutee',
                    'text-[#D9D9D9]' => $role == 'tutor',
                ])>
                    <strong class="block font-medium">{{ Auth::user()->fname . ' ' . Auth::user()->lname }}</strong>

                    <span>{{ Auth::user()->email }}</span>
                </p>
            </div>
        </a>

        <!-- Logout -->
        <button wire:click='logout' @class([
            'inline-flex items-center gap-3 text-sm font-medium p-4 pb-6',
            'text-[#0C3B2E]' => $role == 'tutee',
            'text-[#D9D9D9]' => $role == 'tutor',
        ])>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-logout">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                <path d="M9 12h12l-3 -3" />
                <path d="M18 15l3 -3" />
            </svg>
            Log Out
        </button>
    </div>
</div>