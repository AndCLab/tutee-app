<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public $title = 'Login | Tutee';

    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if (Auth::check()) {
            $role = Auth::user()->user_type;
            if ($role == 'tutee') {
                $this->redirectIntended(default: route('tutee.discover', absolute: false), navigate: true);
            } else if($role == 'tutor'){
                $this->redirectIntended(default: route('tutor.discover', absolute: false), navigate: true);
            }
        }

    }
}; ?>

<div class="max-w-sm mx-auto">
    @push('title')
        {{ $title }}
    @endpush
    
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <div class="flex flex-col gap-4">
            <!-- Email Address -->
            <div>
                <x-wui-input label="Email" placeholder="Enter your email"
                    wire:model="form.email" autocomplete='username'/>
            </div>

            <!-- Password -->
            <div>
                <x-wui-inputs.password placeholder='Enter your password' wire:model="form.password" label="Password"
                    autocomplete="current-password" />
            </div>

            <x-primary-button type='submit'>
                Login
            </x-primary-button>

            <!-- Remember Me -->
            <div class="block">
                <label for="remember" class="inline-flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>
        </div>

        <hr class="my-2">

        <div class="flex flex-col sm:flex-row gap-4 sm:items-center justify-between">
            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            @if (Route::has('register'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('register') }}" wire:navigate>
                    {{ __('Dont\' have an account?') }}
                </a>
            @endif
        </div>
    </form>
</div>
