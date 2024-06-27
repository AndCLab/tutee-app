<div class="md:w-3/4 mx-auto">
    <h1 class="text-[#0C3B2E] text-center text-3xl font-extrabold mb-4 md:mb-10">Choose your Initial Role</h1>
    <div class="grid grid-cols-2 gap-5">
        <div>
            <input type="radio" id="tutee" name="tutee" value="tutee" class="hidden peer/tutee" wire:model.defer="user_type"/>
            <label for="tutee"
                class="inline-flex items-center justify-between w-full p-5 text-black bg-white border border-[#CBD5E1] rounded-lg cursor-pointer peer-checked/tutee:border-[#0C3B2E] peer-checked/tutee:text-[#0C3B2E] peer-checked/tutee:hover:bg-white peer-checked/tutee:cursor-default hover:text-gray-600 hover:bg-gray-100">
                <div class="mx-auto">
                    <img class="size-52 mix-blend-multiply object-contain" src="{{ asset('images/tutee-role.jpg') }}" alt="">
                    <div class="w-full text-center text-lg font-semibold">I am a Tutee</div>
                </div>
            </label>
        </div>
        <div>
            <input type="radio" id="tutor" name="tutor" value="tutor" class="hidden peer/tutor" wire:model.defer="user_type"/>
            <label for="tutor"
                class="inline-flex items-center justify-between w-full p-5 text-black bg-white border border-[#CBD5E1] rounded-lg cursor-pointer peer-checked/tutor:border-[#0C3B2E] peer-checked/tutor:text-[#0C3B2E] peer-checked/tutor:hover:bg-white peer-checked/tutor:cursor-default hover:text-gray-600 hover:bg-gray-100">
                <div class="mx-auto">
                    <img class="size-52 mix-blend-multiply object-contain" src="{{ asset('images/tutor-role.jpg') }}" alt="">
                    <div class="w-full text-center text-lg font-semibold">I am a Tutor</div>
                </div>
            </label>
        </div>
    </div>
    @error('user_type')
        {{ $message }}
    @enderror
</div>
