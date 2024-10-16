<?php

use App\Livewire\Forms\AdminLoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public $title = 'Login | Admin';

    public AdminLoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function adminLogin(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if (Auth::guard('admin')->check()) {
            $this->redirect(route('verify-request.admin', absolute: false), navigate: true);
        }

    }
}; ?>

<div class="max-w-sm mx-auto">
    @push('title')
        {{ $title }}
    @endpush

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="adminLogin">
        <div class="flex flex-col gap-4">
            <!-- Email Address -->
            <div>
                <x-wui-input class="bg-[#CBD5E1] placeholder:text-[#0F172A]" placeholder="Admin" disabled
                    wire:model="form.email" autocomplete='username' shadowless/>
            </div>

            <!-- Password -->
            <div>
                <x-wui-inputs.password placeholder='Enter your password' wire:model="form.password" label="Password"
                    autocomplete="current-password" shadowless/>
            </div>

            <x-admin-button wireTarget='adminLogin'>
                Login
            </x-admin-button>

        </div>

    </form>
</div>
