<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use WireUi\Traits\Actions;


new class extends Component
{
    use Actions;

    public string $fname = '';
    public string $lname = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->fname = Auth::user()->fname;
        $this->lname = Auth::user()->lname;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->notification([
            'title'       => 'Profile saved!',
            'description' => 'Your profile has successfully updated',
            'icon'        => 'success',
            'timeout'     => 3000
        ]);

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-3">
        <div class="inline-flex items-center gap-4">
            <!-- First Name -->
            <div>
                <x-wui-input label="First Name" placeholder="Enter your first name" wire:model="fname" autofocus
                    autocomplete="fname" hint='Update your first name'/>
            </div>

            {{-- Last Name --}}
            <div>
                <x-wui-input label="Last Name" placeholder="Enter your last name" wire:model="lname" autofocus
                    autocomplete="lname" hint='Update your last name'/>
            </div>
        </div>

        <div>
            <!-- Email Address -->
            <div>
                <x-wui-input label="Email" placeholder="Email" wire:model="email" autocomplete='username' hint='Update your email'/>
            </div>

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="grid grid-cols-2 items-center gap-4">
            <x-secondary-button type='submit' class="w-full">{{ __('Save') }}</x-secondary-button>
            {{-- <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message> --}}
    </form>
            {{-- Should be outside the form --}}
            <livewire:profile.delete-user-form/>
        </div>
    <x-wui-notifications position="bottom-right" />
</section>
