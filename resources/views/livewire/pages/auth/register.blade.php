<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public $title = 'Register | Tutee';

    public string $fname = '';
    public string $lname = '';
    public string $email = '';
    public string $zip_code = '';
    public string $phone_number = '';
    public string $address = '';
    public string $password = '';
    public string $password_confirmation = '';

    public string $phone_prefix = '';
    public string $tempPhoneStorage = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        // sample phone number: 9562398125
        $this->tempPhoneStorage = $this->phone_number;

        // for validation
        // merge prefix with phone number: +639562398125
        $this->phone_number = "{$this->phone_prefix}{$this->phone_number}";

        try {
            $validated = $this->validate([
                'fname' => ['required', 'string', 'max:255'],
                'lname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'zip_code' => ['required', 'numeric'],
                'phone_prefix' => ['required', 'string'],
                'phone_number' => ['required', 'string', 'phone'],
                'address' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ], [
                'fname.required' => 'The first name is required',
                'lname.required' => 'The last name is required',
            ]);
        } catch (\Throwable $th) {
            // clear validation errors
            $this->reset('phone_number', 'password', 'password_confirmation');
            throw $th;
        }

        // resets to without prefix: 9562398125
        $validated['phone_number'] = $this->tempPhoneStorage;

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        $this->reset('phone_number');

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="max-w-sm mx-auto">
    @push('title')
        {{ $title }}
    @endpush

    <form wire:submit="register">
        <div class="flex flex-col gap-4">
            <div class="sm:inline-flex sm:items-center gap-2 space-y-4 sm:space-y-0">
                <!-- First Name -->
                <div>
                    <x-wui-input
                        label="First Name"
                        placeholder="Enter your first name"
                        wire:model="fname"
                        autofocus
                        autocomplete="fname"
                        errorless
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
                        errorless
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
                    errorless
                />
            </div>

            {{-- Address --}}
            <div>
                <x-wui-input
                    label="Address"
                    placeholder="Address"
                    wire:model="address"
                    autocomplete='address'
                    errorless
                />
            </div>

            {{-- Zip Code --}}
            <div>
                <x-wui-inputs.maskable
                    label="Zip Code"
                    placeholder="1234"
                    mask="#####"
                    wire:model="zip_code"
                    autocomplete='postal-code'
                    errorless
                />
            </div>

            <div class="flex flex-col md:items-start">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Phone Number
                </label>
                <div class="md:inline-flex space-y-2 md:space-y-0 md:gap-2">
                    <div class="md:w-5/6">
                        <x-wui-select
                            placeholder="Phone Prefix"
                            :async-data="route('phone-prefix')"
                            option-label="phone_code"
                            option-description="country_name"
                            option-value="phone_code"
                            wire:model='phone_prefix'
                            :template="[
                                'name'   => 'user-option',
                                'config' => ['src' => 'country_image']
                            ]"
                            errorless
                        />
                    </div>

                    {{-- Phone Number --}}
                    <div class="w-full">
                        <x-wui-inputs.phone
                            wire:model='phone_number'
                            placeholder='Enter your phone number'
                            mask="[
                                    '####-####',
                                    '### ###-####',
                                    '### ###-#####',
                                    '##### #######',
                                ]"
                            errorless
                        />
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div>
                <x-wui-inputs.password 
                    placeholder='Enter your password'
                    wire:model='password' 
                    label="Password"
                    autocomplete="new-password"
                    errorless
                />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-wui-inputs.password 
                    placeholder='Re-enter your password' 
                    wire:model="password_confirmation"
                    label="Confirm Password" 
                    autocomplete="new-password" 
                    errorless
                />
            </div>

            <x-primary-button wireTarget='register'>
                {{ __('Register') }}
            </x-primary-button>
            <x-wui-errors />
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
