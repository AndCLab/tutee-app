<div class="flex flex-col justify-center items-center w-full sm:py-5 gap-2">
    <div class="grid sm:grid-cols-4 sm:w-3/4 w-full">
        <p class="font-semibold">Work Experience</p>
        <div class="col-span-3 space-y-3 mb-5">
            @foreach ($inputs as $index => $input)
                <div class="flex gap-3">
                    <div class="space-y-3">
                        <div class="sm:inline-flex w-full gap-2">
                            {{-- From --}}
                            <x-wui-select wire:model.live="from.{{ $index }}" placeholder="From" :async-data="route('dates')"
                                option-label="year" option-value="id" autocomplete="off" />

                            {{-- To --}}
                            <x-wui-select wire:model.live="to.{{ $index }}" placeholder="To" :async-data="route('dates')"
                                option-label="year" option-value="id" autocomplete="off" />
                        </div>

                        {{-- Input Work Experience --}}
                        <x-wui-input class="w-full" id="work.{{ $index }}" name="work.{{ $index }}"
                            placeholder="Work Experience" wire:model='work.{{ $index }}' />
                    </div>
                    <div>
                        {{-- Remove Work --}}
                        <x-delete-icon wire:click='remove_work({{ $index }})'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-trash">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" />
                                <path
                                    d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" />
                            </svg>
                        </x-delete-icon>
                    </div>
                </div>
            @endforeach
            {{-- Add Work --}}
            <x-white-button class="w-full mt-3" wire:click='add_work' emerald label="Add Work Experience">
                Add Work Experience</x-white-button>
        </div>

        {{-- Upload Certificate --}}
        <p class="font-semibold sm:py-3">Certificates</p>
        <div class="col-span-3 mb-5">
            <label for="upload-certificate" @class([
                'rounded-md cursor-pointer h-fit w-full flex',
                'hover:outline-[#0F172A] outline-1 outline-dashed outline-[#0F172A]/70' => !$certificate,
                'hover:outline-emerald-800 outline-1 outline-none outline-emerald-800/70' => $certificate,
            ])>
                <div @class([
                    'flex flex-col justify-center items-center w-full py-3 border-transparent',
                    'text-[#0F172A]/70 hover:text-[#0F172A]' => !$certificate,
                    'text-emerald-800/70 hover:text-emerald-800' => $certificate,
                ])>
                    @if ($certificate)
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-cloud-upload">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1" />
                            <path d="M9 15l3 -3l3 3" />
                            <path d="M12 12l0 9" />
                        </svg>
                    @endif

                    <span class="font-medium text-sm" style="line-height: 30px">
                        @if (!$certificate)
                            <div wire:loading.class='hidden' wire:target="certificate">
                                Upload Certificate
                            </div>
                            <div wire:loading wire:target="certificate">
                                Uploading...
                            </div>
                        @else
                            <div wire:loading.class='hidden' wire:target="certificate">
                                Uploaded!
                            </div>
                            <div wire:loading wire:target="certificate">
                                Uploading...
                            </div>
                        @endif
                    </span>
                </div>
            </label>
            <input wire:model="certificate" class="hidden" type="file" accept=".pdf,.png,.jpg,.jpeg"
                id="upload-certificate" multiple>

            @error('certificate')
                <p class="text-[#dc2626] pt-2 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Upload Resume --}}
        <p class="font-semibold sm:py-3">Resume</p>
        <div class="col-span-3 mb-5">
            <label for="upload-resume" @class([
                'rounded-md cursor-pointer h-fit w-full flex',
                'hover:outline-[#0F172A] outline-1 outline-dashed outline-[#0F172A]/70' => !$resume,
                'hover:outline-emerald-800 outline-1 outline-none outline-emerald-800/70' => $resume,
            ])>
                <div @class([
                    'flex flex-col justify-center items-center w-full py-3 border-transparent',
                    'text-[#0F172A]/70 hover:text-[#0F172A]' => !$resume,
                    'text-emerald-800/70 hover:text-emerald-800' => $resume,
                ])>
                    @if ($resume)
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-cloud-upload">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1" />
                            <path d="M9 15l3 -3l3 3" />
                            <path d="M12 12l0 9" />
                        </svg>
                    @endif

                    <span class="font-medium text-sm" style="line-height: 30px">
                        @if (!$resume)
                            <div wire:loading.class='hidden' wire:target="resume">
                                Upload Resume
                            </div>
                            <div wire:loading wire:target="resume">
                                Uploading...
                            </div>
                        @else
                            <div wire:loading.class='hidden' wire:target="resume">
                                Uploaded!
                            </div>
                            <div wire:loading wire:target="resume">
                                Uploading...
                            </div>
                        @endif
                    </span>
                </div>
            </label>
            <input wire:model="resume" class="hidden" type="file" accept=".pdf" name="resume"
                id="upload-resume">

            @error('resume')
                <p class="text-[#dc2626] pt-2 text-sm">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
