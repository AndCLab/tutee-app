<div class="col-span-3 mb-5">
    <label for="upload-certificate" class="
        rounded-lg
        cursor-pointer
        h-40
        w-100  shadow-xl
        flex
        hover:outline-2
        hover:outline-collapse
        hover:outline-dashed
        hover:outline-neutral-300
        hover:brightness-50
        ">
        <div class="flex flex-col justify-center items-center w-full">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="text-neutral-400 w-10 h-10">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm3 14a1 1 0 011-1v-8a1 1 0 011-1h2a1 1 0 011 1v8a1 1 0 01-1 1zm4-2a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1z" clip-rule="evenodd" />
            </svg>
            <span class="text-neutral-400 text-sm pt-2" style="line-height: 30px">Upload Certificates</span>
        </div>
    </label>
    <input wire:model="certificate" type="file" accept=".pdf,.png,.jpg,.jpeg" id="upload-certificate">

    @error('certificate')
        <span class="error">{{ $message }}</span>
    @enderror
</div>