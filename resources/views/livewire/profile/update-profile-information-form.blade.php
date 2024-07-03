<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Livewire\WithFileUploads;
use Livewire\Volt\Component;
use WireUi\Traits\Actions;

new class extends Component
{
    use Actions, WithFileUploads;

    public string $fname = '';
    public string $lname = '';
    public string $email = '';
    public $avatar;
    public $cropped_image;
    public $uploadInput;

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
     * Update the profile profile information for the currently authenticated user.
     */
    public function saveCroppedImage()
    {
        $image = $this->cropped_image;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = 'avatars/cropped_image_' . time() . '.png';

        Storage::disk('public')->put($imageName, base64_decode($image));

        $user = Auth::user();
        if ($user->avatar && $user->avatar != 'default.png') {
            $old_path = public_path('storage/' . $user->avatar);
            if (File::exists($old_path)) {
                File::delete($old_path);
            }
        }

        $user->avatar = $imageName;
        $user->save();

        $this->reset('uploadInput');

        $this->notification([
            'title'       => 'Profile picture saved!',
            'description' => 'Your profile picture has been successfully updated',
            'icon'        => 'success',
            'timeout'     => 3000
        ]);

    }

    public function removeAvatar()
    {
        $user = Auth::user();

        // dd($user);

        if ($user->avatar && $user->avatar != 'default.png') {
            $old_path = public_path('storage/' . $user->avatar);
            if (File::exists($old_path)) {
                File::delete($old_path);
            }
        }

        $user->avatar = 'default.png';
        $user->save();

        $this->reset('uploadInput');

        $this->notification([
            'title'       => 'Profile picture saved!',
            'description' => 'Your profile picture has successfully removed',
            'icon'        => 'success',
            'timeout'     => 3000
        ]);

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
    <header class="mb-2">
        <h2 class="text-lg font-semibold text-gray-900">
            {{ __('Account Settings') }}
        </h2>

        <p class="mt-1 text-xs text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    {{-- border-[#F1F5F9] --}}
    <div class="flex justify-between items-end gap-2">
        <div class="size-20">
            @if (Auth::user()->avatar !== 'default.png')
                <img class="rounded-lg" src="{{ asset('storage/' . Auth::user()->avatar) }}">
            @else
                <img class="rounded-lg" src="{{ asset('images/' . Auth::user()->avatar) }}">
            @endif
        </div>

        <div>
            <div @class([
                    'grid-cols-1' => Auth::user()->avatar === 'default.png',
                    'grid-cols-2' => Auth::user()->avatar !== 'default.png'
                ])>

                @if (Auth::user()->avatar !== 'default.png')
                    <x-wui-button negative xs flat type='submit' label='Remove' wire:click.prevent='removeAvatar' spinner='removeAvatar'/>
                @endif
                <x-wui-button flat xs type='button' onclick="$openModal('simpleModal')" label='Change Profile' />
            </div>
        </div>
    </div>

    <x-wui-modal name="simpleModal" align='center' max-width='sm' persistent>
        <x-wui-card title="Profile Picture">
            <p class="text-gray-600">

                <div class="flex items-center justify-center w-full" id="uploadContainer">
                    <label for="uploadInput" id="uploadLabel" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                        </div>
                        {{-- image input --}}
                        <input type="file" name="uploadInput" class="hidden" id="uploadInput" accept="image/png, image/jpeg">
                    </label>
                </div>

                <div class="max-w-full">
                    <img id="avatar" >
                </div>
                <img id="cropped_image" class="max-w-full hidden">
            </p>

            <form wire:submit.prevent="saveCroppedImage">
                <input type="hidden" wire:model="cropped_image" id="croppedImage">
            </form>
            <span class="mt-2 text-sm text-negative-600 hidden" id="uploadError">You have not uploaded any image yet.</span>
            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-wui-button flat label="Cancel" x-on:click="close" id="uploadClose"/>
                    <x-wui-button primary label="Save Cropped Image" spinner id="saveAndCrop"/>
                </div>
            </x-slot>
        </x-wui-card>
    </x-wui-modal>

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

    {{-- Notification --}}
    <x-wui-notifications position="bottom-right" />

    {{-- CropperJS Script --}}
    <script>
        const image = document.getElementById("avatar");
        let cropper;

        // File input: if file exists then image gets the src of the uploaded image and cropperJS
        // will handle the cropping feature
        document.getElementById("uploadInput").addEventListener("change", function (event) {
            const file = event.target.files[0];
            if (file) {
                image.src = URL.createObjectURL(file);
                image.onload = () => {
                    // if there's a cropper opened. it will destroy and make a new Cropper object
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        movable: false,
                        zoomOnWheel: false,
                        minCropBoxWidth: 200,
                        minCropBoxHeight: 200,
                    });
                };
                document.getElementById("uploadContainer").classList.add('hidden');
                document.getElementById("uploadError").classList.add('hidden');
            }
        });

        // Submit: if file's cropped, it generates a data URL:

        // toDataURL: it returns a data URL containing a representation of the image
        // in the format specified by the type parameter

        // afterwards, cropped_image gets that image and store it inside croppedImage livewire property
        // it also calls the livewire function "saveCroppedImage" for storing inside database :)
        document.getElementById("saveAndCrop").addEventListener("click", () => {
            if (cropper) {
                const croppedCanvas = cropper.getCroppedCanvas();
                if(croppedCanvas){

                    const croppedImage = croppedCanvas.toDataURL("image/png");
                    document.getElementById("cropped_image").src = croppedImage;
                    @this.set('cropped_image', croppedImage, true).then(() => {
                        @this.call('saveCroppedImage');
                    }).then(() => {
                        image.src = '';
                        if(cropper) cropper.destroy();
                        @this.set('cropped_image', null, true);
                        document.getElementById("cropped_image").src = '';
                        document.getElementById("uploadInput").value = '';
                    });
                    console.log('save and crop if');
                } else{
                    console.log('save and crop else');
                    document.getElementById("uploadLabel").classList.remove('border-gray-300');
                    document.getElementById("uploadLabel").classList.add('border-negative-300');
                    document.getElementById("uploadError").classList.remove('hidden');
                }
            } else{
                document.getElementById("uploadLabel").classList.remove('border-gray-300');
                document.getElementById("uploadLabel").classList.add('border-negative-300');
                document.getElementById("uploadError").classList.remove('hidden');
            }
        });

        document.getElementById("uploadClose").addEventListener("click", () => {
            setTimeout(() => {
                // Reset values upon closing modal
                image.src = '';
                if(cropper) cropper.destroy();
                @this.set('cropped_image', null, true);
                document.getElementById("cropped_image").src = '';
                document.getElementById("uploadInput").value = '';

                // Reset styles
                document.getElementById("uploadContainer").classList.remove('hidden');
                document.getElementById("uploadLabel").classList.remove('border-negative-300');
                document.getElementById("uploadLabel").classList.add('border-gray-300');
                document.getElementById("uploadError").classList.add('hidden');
            }, 100);
        });
    </script>
</section>
