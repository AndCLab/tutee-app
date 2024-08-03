<?php

use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\Volt\Component;
use WireUi\Traits\Actions;
use Livewire\Attributes\On;

new class extends Component
{
    use Actions, WithFileUploads;

    public string $fname = '';
    public string $lname = '';

    public $avatar;
    public $cropped_image;
    public $showModal;

    public function mount(): void
    {
        $this->fname = Auth::user()->fname;
        $this->lname = Auth::user()->lname;
    }

    /**
     * Update the profile profile information for the currently authenticated user.
     */
    public function saveCroppedImage()
    {
        $image = $this->cropped_image;

        if ($image) {
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'avatars/cropped_image_' . time() . '.png';

            Storage::disk('public')->put($imageName, base64_decode($image));

            $user = Auth::user();
            if ($user->avatar && $user->avatar != null) {
                $old_path = $user->avatar;
                if (Storage::disk('public')->exists($old_path)) {
                    Storage::disk('public')->delete($old_path);
                }
            }

            $user->avatar = $imageName;
            $user->save();

            $this->notification([
                'title'       => 'Profile picture saved!',
                'description' => 'Your profile picture has been successfully updated',
                'icon'        => 'success',
                'timeout'     => 3000
            ]);

            $this->showModal = false;

            $changedAvatar = Storage::url(Auth::user()->avatar);
            $this->dispatch('avatar-path', avatar: $changedAvatar);
        }

    }

    public function removeAvatar()
    {
        $user = Auth::user();

        // dd($user);

        if ($user->avatar && $user->avatar != null) {
            $old_path = $user->avatar;
            if (Storage::disk('public')->exists($old_path)) {
                Storage::disk('public')->delete($old_path);
            }
        }

        $user->avatar = null;
        $user->save();

        $this->notification([
            'title'       => 'Profile picture saved!',
            'description' => 'Your profile picture has successfully removed',
            'icon'        => 'success',
            'timeout'     => 3000
        ]);

        $defaultProfile = asset('images/default.jpg');
        $this->dispatch('removed-avatar', defaultProfile: $defaultProfile);
    }

}; ?>

<section>
    {{-- border-[#F1F5F9] --}}
    <div class="flex justify-between items-end gap-2">
        <div class="size-20">
            @if (Auth::user()->avatar !== null)
                <img class="border-2 rounded-lg border-[#F1F5F9] size-20 overflow-hidden" src="{{ Storage::url(Auth::user()->avatar) }}">
            @else
                <img class="border-2 rounded-lg border-[#F1F5F9] size-20 overflow-hidden" src="{{ asset('images/default.jpg') }}">
            @endif
        </div>

        <div>
            <div @class([
                    'grid-cols-1' => Auth::user()->avatar === null,
                    'grid-cols-2' => Auth::user()->avatar !== null
                ])>

                @if (Auth::user()->avatar !== null)
                    <x-wui-button negative xs flat type='submit' label='Remove' wire:click.prevent='removeAvatar' icon='trash' spinner='removeAvatar'/>
                @endif
                <x-wui-button flat xs type='button' wire:click="$set('showModal', true)" label='Change Profile' spinner='saveCroppedImage' />
            </div>
        </div>
    </div>

    <x-wui-modal wire:model.defer="showModal" align='center' max-width='md' persistent>
        <x-wui-card title="Profile Picture">
            <p class="text-gray-600">

                <div class="flex items-center justify-center w-full" id="uploadContainer">
                    <label for="uploadInput" id="uploadLabel" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX: 2MB)</p>
                        </div>
                        {{-- image input --}}
                        <input type="file" name="uploadInput" class="hidden" id="uploadInput" accept="image/png, image/jpeg">
                    </label>
                </div>

                <div class="flex gap-3">
                    <div class="h-auto w-2/3">
                        <img id="avatar" class="max-w-full">
                    </div>
                    <div class="flex-col justify-start gap-2 hidden" id="previewContainer">
                        <div class="border-2 rounded-md border-[#F1F5F9] size-20 overflow-hidden" id="preview"></div>
                        <p class="text-xs text-gray-500">Profile Preview</p>
                    </div>
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
                    <x-wui-button primary label="Save Profile" spinner='saveCroppedImage' id="saveAndCrop"/>
                </div>
            </x-slot>
        </x-wui-card>
    </x-wui-modal>

    {{-- CropperJS Script --}}
    <script data-navigate-once>
        document.addEventListener('livewire:navigated', function () {
            const image = document.getElementById("avatar");
            const preview = document.getElementById("preview");
            const previewContainer = document.getElementById("previewContainer");
            const uploadInput = document.getElementById("uploadInput");
            const uploadContainer = document.getElementById("uploadContainer");
            const uploadError = document.getElementById("uploadError");
            const uploadLabel = document.getElementById("uploadLabel");
            const croppedImageElement = document.getElementById("cropped_image");
            const saveAndCropButton = document.getElementById("saveAndCrop");
            const uploadCloseButton = document.getElementById("uploadClose");
            const maxFileSize = 2 * 1024 * 1024;
            let cropper;

            // reset function for clearing upon closing the modal
            function resetUpload() {
                image.src = '';
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                @this.set('cropped_image', null, true);
                croppedImageElement.src = '';
                uploadInput.value = '';

                uploadContainer.classList.remove('hidden');
                uploadLabel.classList.remove('border-negative-300');
                uploadLabel.classList.add('border-gray-300');
                uploadError.classList.add('hidden');
            }

            // File input: if file exists then image gets the src of the uploaded image and cropperJS
            // will handle the cropping feature
            if (uploadInput) {
                uploadInput.addEventListener("change", function (event) {
                    const file = event.target.files[0];
                    if (file) {
                        if (file.size > maxFileSize) {
                            window.$wireui.notify({
                                title: 'File size exceeds',
                                description: 'File size should not exceed 2MB',
                                icon: 'error'
                            })
                            resetUpload();
                            return;
                        }
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
                                minContainerHeight: 100,
                                minContainerWidth: 100,
                                preview: '#preview'
                            });
                        };
                        previewContainer.classList.remove('hidden');
                        previewContainer.classList.add('flex');
                        uploadContainer.classList.add('hidden');
                        uploadError.classList.add('hidden');
                    }
                });
            }

            // Submit: if file's cropped, it generates a data URL:

            // toDataURL: it returns a data URL containing a representation of the image
            // in the format specified by the type parameter

            // afterwards, cropped_image gets that image and store it inside croppedImage livewire property
            // it also calls the livewire function "saveCroppedImage" for storing inside database :)
            if (saveAndCropButton) {
                saveAndCropButton.addEventListener("click", () => {
                    if (cropper) {
                        const croppedCanvas = cropper.getCroppedCanvas();
                        if (croppedCanvas) {
                            const croppedImage = croppedCanvas.toDataURL("image/png");
                            croppedImageElement.src = croppedImage;

                            // sets cropped_image value of croppedImage and call the saveCroppedImage function
                            // then resets the modal
                            @this.set('cropped_image', croppedImage, true).then(() => {
                                @this.call('saveCroppedImage');
                            }).then(resetUpload);
                        }
                    } else {
                        uploadLabel.classList.remove('border-gray-300');
                        uploadLabel.classList.add('border-negative-300');
                        uploadError.classList.remove('hidden');
                    }
                });
            }

            // sets 50ms and call the resetUpload function
            if (uploadCloseButton) {
                uploadCloseButton.addEventListener("click", () => {
                    setTimeout(resetUpload, 50);
                });
            }
        });
    </script>
</section>
