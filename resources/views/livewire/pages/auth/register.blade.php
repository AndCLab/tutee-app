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
    public string $zip_code = '';
    public string $phone_number = '';
    public string $address = '';
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
            'zip_code' => ['required'],
            'phone_number' => ['required', 'numeric'],
            'address' => ['required', 'string', 'max:255'],
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
                    <x-wui-input 
                        label="First Name" 
                        placeholder="Enter your first name" 
                        wire:model="fname" 
                        autofocus
                        autocomplete="fname" 
                    />
                </div>

                {{-- Last Name --}}
                <div>
                    <x-wui-input 
                        label="Last Name" 
                        placeholder="Enter your last name" 
                        wire:model="lname" 
                        autofocus
                        autocomplete="lname" 
                    />
                </div>
            </div>

            <!-- Email Address -->
            <div>
                <x-wui-input 
                    label="Email" 
                    placeholder="Email" 
                    wire:model="email" 
                    autocomplete='username'
                />
            </div>

            {{-- Address --}}
            <div>
                <x-wui-input 
                    label="Address" 
                    placeholder="Address" 
                    wire:model="address" 
                    autocomplete='address' 
                />
            </div>

            {{-- Zip Code --}}
            <div>
                <x-wui-inputs.maskable
                    label="Zip Code"
                    placeholder="1234"
                    mask="#####"
                    wire:model="zip_code"
                    autocomplete='zip_code'
                />
            </div>

            {{-- Phone Number --}}
            <div>
                <x-wui-inputs.phone 
                    label="Phone" 
                    wire:model='phone_number' 
                    placeholder='Enter your phone number' 
                    mask="[
                        '(+##) ####-####', 
                        '(+##) #####-####', 
                        '(+##) ### ###-#####',
                        '(+###) ### ###-#####',
                        '(+####) ### ###-#####'
                    ]" />
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

        </div>
        <hr class="my-2 mt-4">

        <div class="flex items-center justify-start">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}" wire:navigate>
                {{ __('I have an account') }}
            </a>
        </div>
    </form>
</div>
