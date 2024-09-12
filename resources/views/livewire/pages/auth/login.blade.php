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
            $user = Auth::user();

            if ($user->is_stepper == 1) {
                $route = 'stepper';
            } else {
                $route = $user->user_type == 'tutee' ? 'tutee.discover' : 'tutor.discover';
            }

            $this->redirectIntended(route($route), navigate: true);
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
                    wire:model="form.email" autocomplete='username' shadowless/>
            </div>

            <!-- Password -->
            <div>
                <x-wui-inputs.password placeholder='Enter your password' wire:model="form.password" label="Password"
                    autocomplete="current-password" shadowless/>
            </div>

            {{-- <x-wui-button type='submit' spinner='login' class="ring-[#0C3B2E] text-white bg-[#0C3B2E] hover:bg-[#0C3B2E] hover:ring-[#0C3B2E]" label='Login' /> --}}

            <x-primary-button wireTarget='login'>
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

        <div class="flex flex-col items-center gap-2">
            <!-- "OR CONTINUE WITH" Text -->
            <p class="text-sm font-bold text-gray-600 mb-4 mt-4">OR CONTINUE WITH</p>

            <!-- Google Login Button -->
            <a id="google-login-btn" href="{{ route('google.login') }}" class="flex items-center justify-center w-full py-2 px-4 border border-gray-300 text-gray-700 text-base font-bold rounded hover:border-gray-400">
                <i class="fab fa-google mr-2"></i> {{ __('Login with Google') }}
            </a>

            <!-- Facebook Login Button -->
            <a id="facebook-login-btn" href="{{ route('facebook.login') }}" class="flex items-center justify-center w-full py-2 px-4 border border-gray-300 text-gray-700 text-base font-bold rounded hover:border-gray-400">
                <i class="fab fa-facebook mr-2"></i> {{ __('Login with Facebook') }}
            </a>
        </div>
        
    </form>
</div>
