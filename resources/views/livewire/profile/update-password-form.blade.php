<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;
use WireUi\Traits\Actions;

new class extends Component
{
    use Actions;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');


        $this->notification([
            'title'       => 'Password saved!',
            'description' => 'Your password has successfully updated',
            'icon'        => 'success',
            'timeout'     => 3000
        ]);


        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form wire:submit="updatePassword" class="mt-6 space-y-3">
        <!-- Old Password -->
        <div>
            <x-wui-inputs.password placeholder='Enter your current password' wire:model="current_password" label="Password"
                autocomplete="current_password" id="current_password"/>
        </div>

        <!-- New Password -->
        <div>
            <x-wui-inputs.password placeholder='Enter your new password' wire:model="password" label="New Password"
                autocomplete="new-password" id="password"/>
        </div>

        <!-- Confirm Password -->
        <div>
            <x-wui-inputs.password placeholder='Confirm password' wire:model="password_confirmation"
                label="Password" autocomplete="new-password" id="password_confirmation"/>
        </div>

        <div class="flex items-center gap-4">
            <x-secondary-button type='submit' class="w-full">{{ __('Save') }}</x-secondary-button>

            <x-action-message class="me-3" on="password-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
    <x-wui-notifications position="bottom-right" />
</section>
