<div class="w-2/3 mx-auto">
    <div class="mb-5">
        <input type="radio" id="tutee" name="tutee" value="tutee" class="hidden peer/tutee" wire:model.defer="user_type"/>
        <label for="tutee"
            class="inline-flex items-center justify-between w-full p-5 text-black bg-white border border-[#CBD5E1] rounded-lg cursor-pointer peer-checked/tutee:border-[#0C3B2E] peer-checked/tutee:text-[#0C3B2E] hover:text-gray-600 hover:bg-gray-100">
            <div class="block">
                <div class="w-full text-lg font-semibold">I am a Tutee</div>
            </div>

        </label>
    </div>
    <div class="mb-5">
        <input type="radio" id="tutor" name="tutor" value="tutor" class="hidden peer/tutor" wire:model.defer="user_type"/>
        <label for="tutor"
            class="inline-flex items-center justify-between w-full p-5 text-black bg-white border border-[#CBD5E1] rounded-lg cursor-pointer peer-checked/tutor:border-[#0C3B2E] peer-checked/tutor:text-[#0C3B2E] hover:text-gray-600 hover:bg-gray-100">
            <div class="block">
                <div class="w-full text-lg font-semibold">I am a Tutor</div>
            </div>
        </label>
    </div>
    @error('user_type')
        {{ $message }}
    @enderror
</div>
