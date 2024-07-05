<div class="md:w-3/4 mx-auto">
    <h1 class="text-[#0C3B2E] text-center text-2xl md:text-3xl font-extrabold mb-4 md:mb-10">Choose your Initial Role</h1>
    <div class="grid md:grid-cols-2 gap-5">
        <div>
            <input type="radio" id="tutee" name="tutee" value="tutee" class="hidden peer/tutee"
                wire:model.defer="user_type" />
            <label for="tutee"
                class="inline-flex items-center justify-between w-full p-5 text-black bg-white border border-[#CBD5E1] rounded-lg cursor-pointer peer-checked/tutee:border-[#0C3B2E] peer-checked/tutee:text-[#0C3B2E] peer-checked/tutee:hover:bg-white peer-checked/tutee:cursor-default hover:text-gray-600 hover:bg-gray-100">
                <div class="mx-auto">
                    <img class="hidden md:block size-52 mix-blend-multiply object-contain" src="{{ asset('images/tutee-role.jpg') }}"
                        alt="">
                    <div class="w-full text-center text-lg font-semibold">I am a Tutee</div>
                </div>
            </label>
        </div>
        <div>
            <input type="radio" id="tutor" name="tutor" value="tutor" class="hidden peer/tutor"
                wire:model.defer="user_type" />
            <label for="tutor"
                class="inline-flex items-center justify-between w-full p-5 text-black bg-white border border-[#CBD5E1] rounded-lg cursor-pointer peer-checked/tutor:border-[#0C3B2E] peer-checked/tutor:text-[#0C3B2E] peer-checked/tutor:hover:bg-white peer-checked/tutor:cursor-default hover:text-gray-600 hover:bg-gray-100">
                <div class="mx-auto">
                    <img class="hidden md:block size-52 mix-blend-multiply object-contain" src="{{ asset('images/tutor-role.jpg') }}"
                        alt="">
                    <div class="w-full text-center text-lg font-semibold">I am a Tutor</div>
                </div>
            </label>
        </div>
    </div>
    @error('user_type')
        <div class="bg-negative-50 mt-3 dark:bg-negative-900/70  shadow w-full flex flex-col p-4 rounded-md">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <svg class="text-negative-800 dark:text-negative-200 w-5 h-5 mr-3 shrink-0" stroke="currentColor"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M9.75 9.75L14.25 14.25M14.25 9.75L9.75 14.25M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>

                    <div class="text-negative-800 dark:text-negative-200 font-normal text-sm whitespace-normal">
                        Please specify your role
                    </div>
                </div>
            </div>
        </div>
    @enderror
</div>
