<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public string $role = '';

    public function mount()
    {
        $this->role = Auth::user()->user_type;
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
        <a href="#" class="flex items-center gap-2 p-4">
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
    </div>
</div>
