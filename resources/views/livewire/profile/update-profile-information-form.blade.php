<?php

use App\Models\User;
use App\Models\Tutor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use WireUi\Traits\Actions;

new class extends Component
{
    use Actions;

    public $tutor;
    public string $fname = '';
    public string $lname = '';
    public string $bio = '';
    public string $email = '';
    public string $address = '';
    public string $zip_code = '';
    public string $phone_prefix = '';
    public string $phone_number = '';
    public string $tempPhoneStorage = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        if (Auth::user()->user_type === 'tutor') {
            $this->tutor = Tutor::where('user_id', Auth::id())->first();
            if ($this->tutor && $this->tutor->bio != null) {
                $this->bio = $this->tutor->bio;
            }
        }

        $this->fname = Auth::user()->fname;
        $this->lname = Auth::user()->lname;
        $this->email = Auth::user()->email;
        $this->address = Auth::user()->address;
        $this->zip_code = Auth::user()->zip_code;
        $this->phone_prefix = Auth::user()->phone_prefix;
        $this->phone_number = Auth::user()->phone_number;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'fname' => ['required'],
            'lname' => ['required'],
            'email' => ['required'],
            'address' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'numeric'],
            'phone_prefix' => ['required'],
            'phone_number' => ['required'],
        ], [
            'fname.required' => 'The first name is required',
            'lname.required' => 'The last name is required',
        ]);

        if ($this->phone_prefix && $this->phone_number) {
            $this->tempPhoneStorage = $this->phone_number;
            $this->phone_number = "{$this->phone_prefix}{$this->phone_number}";
        }

        try {
            $validated = $this->validate([
                'fname' => ['string', 'max:255'],
                'lname' => ['string', 'max:255'],
                'bio' => ['string', 'max:255'],
                'email' => ['string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
                'address' => ['string', 'max:255'],
                'zip_code' => ['string'],
                'phone_prefix' => ['string'],
                'phone_number' => ['string', 'phone'],
            ]);
        } catch (\Throwable $th) {
            $this->phone_number = $this->tempPhoneStorage;
            throw $th;
        }

        $validated['phone_number'] = $this->tempPhoneStorage;
        $this->phone_number = $this->tempPhoneStorage;

        $user->fill($validated);

        $this->tutor->bio = $this->bio;
        $this->tutor->save();

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

        $name = $this->fname . ' ' .$this->lname;

        $this->dispatch('profile-updated', name: $name, email: $this->email);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $route = Auth::user()->user_type == 'tutee' ? 'tutee.discover' : 'tutor.discover';
            $this->redirectIntended(default: route($route, absolute: false), navigate: true);

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header class="mb-2">
        <h2 class="text-lg font-semibold text-gray-900">
            {{ __('Account Settings') }}
        </h2>

        <p class="mt-1 text-xs text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    {{-- profile picture component --}}
    <livewire:profile.profile-picture />

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-3">
        <div class="my-2">
            <x-wui-errors />
        </div>

        {{-- bio --}}
        @if (Auth::user()->user_type === 'tutor')
            <div>
                <x-wui-textarea wire:model='bio' label="Bio" rows='3' class="resize-none soft-scrollbar" placeholder='Update your bio' shadowless/>
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center gap-2">
            <!-- First Name -->
            <x-wui-input label="First Name" placeholder="First Name" wire:model="fname"
                autocomplete="fname" hint='Update your first name' errorless shadowless autofocus/>

            {{-- Last Name --}}
            <x-wui-input label="Last Name" placeholder="Last Name" wire:model="lname"
                autocomplete="lname" hint='Update your last name' errorless shadowless autofocus/>
        </div>

        <div>
            <!-- Email Address -->
            <div>
                <x-wui-input label="Email" placeholder="Email" wire:model="email" autocomplete='username' hint='Update your email' errorless shadowless/>
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

        <!-- Address -->
        <div>
            <x-wui-input
                label="Address"
                placeholder="Address"
                wire:model="address"
                autocomplete='address'
                hint='Update your address'
                errorless
            />
        </div>

        <!-- Zip Code -->
        <div>
            <x-wui-inputs.maskable
                label="Zip Code"
                placeholder="1234"
                mask="#####"
                wire:model="zip_code"
                autocomplete='postal-code'
                hint='Update your zip code'
                errorless
            />
        </div>

        <!-- Phone Number -->
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
                        errorless shadowless
                    />
                </div>

                {{-- Phone Number --}}
                <div class="w-full">
                    <x-wui-inputs.phone
                        wire:model='phone_number'
                        placeholder='Phone Number'
                        mask="[
                                '####-####',
                                '### ###-####',
                                '### ###-#####',
                                '##### #######',
                            ]"
                        errorless shadowless
                    />
                </div>
            </div>
            <label class="mt-1 text-sm text-secondary-500 dark:text-secondary-400">
                Update your phone number
            </label>
        </div>

        <div class="grid md:grid-cols-2 items-center gap-4">
            <x-secondary-button type='submit' wireTarget='updateProfileInformation' class="w-full">{{ __('Update Account') }}</x-secondary-button>
            {{-- <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message> --}}
    </form>
            {{-- Should be outside the form --}}
            <livewire:profile.delete-user-form/>
        </div>

</section>
