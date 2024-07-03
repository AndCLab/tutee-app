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

<div @class([
    'hidden sm:flex sticky top-0 h-screen flex-col justify-between border-e min-w-fit',
    'bg-white' => $role == 'tutee',
    'bg-[#0C3B2E]' => $role == 'tutor',
])>
    <div class="px-4 py-6">
        <h1 @class([
            'uppercase font-bold text-4xl px-2 font-anton mb-4',
            'text-[#0C3B2E]' => $role == 'tutee',
            'text-[#6D9773]' => $role == 'tutor',
        ])>tutee</h1>

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
        <a href="{{ route('profile') }}" wire:navigate @class([
            'flex items-center gap-2 px-2  py-2 rounded-md w-full',
            'hover:bg-[#F2F2F2]' => $role == 'tutee',
            'hover:bg-[#F2F2F2]/10' => $role == 'tutor',
        ])>
            @if (Auth::user()->avatar == 'default.png')
                <img alt="default.png" src="{{ asset('images/' . Auth::user()->avatar) }}"
                    class="size-10 rounded-full object-cover" />
            @else
                <img alt="current avatar" src="{{ asset('storage/' . Auth::user()->avatar) }}"
                    class="size-10 rounded-full object-cover" />
            @endif
            <div>
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
        <button wire:click='logout' @class([
            'inline-flex items-center gap-3 text-sm font-medium px-2 mb-3 py-2 rounded-md w-full',
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
            Log Out
        </button>
    </div>
</div>
