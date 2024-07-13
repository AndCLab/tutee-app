<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $fname = '';
    public string $lname = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="max-w-sm mx-auto">
    <form wire:submit="register">
        <div class="flex flex-col gap-4">
            <div class="sm:inline-flex sm:items-center gap-4 space-y-4 sm:space-y-0">
                <!-- First Name -->
                <div>
                    <x-wui-input label="First Name" placeholder="Enter your first name" wire:model="fname" autofocus
                        autocomplete="fname" />
                </div>

                {{-- Last Name --}}
                <div>
                    <x-wui-input label="Last Name" placeholder="Enter your last name" wire:model="lname" autofocus
                        autocomplete="lname" />
                </div>
            </div>

            <!-- Email Address -->
            <div>
                <x-wui-input label="Email" placeholder="Email" wire:model="email" autocomplete='username' />
            </div>

            <!-- Password -->
            <div>
                <x-wui-inputs.password placeholder='Enter your password' wire:model="password" label="Password"
                    autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-wui-inputs.password placeholder='Enter your password' wire:model="password_confirmation"
                    label="Password" autocomplete="new-password" />
            </div>

            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>

            <div class="flex items-center justify-end">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('login') }}" wire:navigate>
                    {{ __('I have an account') }}
                </a>

            </div>
        </div>
    </form>
</div>
