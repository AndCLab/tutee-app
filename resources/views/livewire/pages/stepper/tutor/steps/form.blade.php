<div class="flex flex-col justify-center items-center w-full py-3 sm:py-5 gap-2">
    <div class="grid sm:grid-cols-4 sm:w-2/3 w-full">
        <p class="font-semibold">Work Experience</p>
        <div class="col-span-3 space-y-3 mb-5">
            @foreach ($inputs as $index => $input)
                <div class="flex gap-3">
                    <div class="space-y-3">
                        <div class="sm:inline-flex w-full gap-2">
                            {{-- From --}}
                            <x-wui-select wire:model.live="from.{{ $index }}"
                                placeholder="From" :async-data="route('dates')"
                                option-label="year" option-value="id" autocomplete="off"/>

                            {{-- To --}}
                            <x-wui-select wire:model.live="to.{{ $index }}"
                                placeholder="To" :async-data="route('dates')"
                                option-label="year" option-value="id" autocomplete="off"/>
                        </div>
                        
                        {{-- Input Work Experience --}}
                        <x-wui-input class="w-full" id="work.{{ $index }}"
                        name="work.{{ $index }}" placeholder="Work Experience"
                        wire:model='work.{{ $index }}' />
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
            <x-wui-errors />

            {{-- Add Work --}}
            <x-white-button class="w-full mt-3" wire:click='add_work' emerald label="Add Work Experience">
                Add Work Experience</x-white-button>
        </div>

        {{-- Upload Certificate--}}
        <p class="font-semibold sm:py-3">Certificates</p>
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
                @if($certificate) border-4 border-emerald-500 @else border-4 border-transparent @endif
                ">
                <div class="flex flex-col justify-center items-center w-full">
                    @if($certificate)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="green" class="w-10 h-10">
                            <circle cx="12" cy="12" r="10" fill="green"/>
                            <path d="M10 15l-3-3 1.414-1.414L10 12.172l5.586-5.586L17 8l-7 7z" fill="white"/>
                        </svg>
                        <span class="text-green-500 text-sm pt-2" style="line-height: 30px">
                            Uploaded Certificate
                        </span>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="text-neutral-400 w-10 h-10">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm3 14a1 1 0 011-1v-8a1 1 0 011-1h2a1 1 0 011 1v8a1 1 0 01-1 1zm4-2a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-neutral-400 text-sm pt-2" style="line-height: 30px">
                            Upload Certificate
                        </span>
                    @endif
                </div>
            </label>
            <input wire:model="certificate" class="hidden" type="file" accept=".pdf,.png,.jpg,.jpeg" id="upload-certificate">
        
            @error('certificate')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        
        {{-- Upload Resume--}}
        <p class="font-semibold sm:py-3">Resume</p>
        <div class="col-span-3">
            <label for="upload-resume" class="
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
                @if($resume) border-4 border-emerald-500 @else border-4 border-transparent @endif
                ">
                <div class="flex flex-col justify-center items-center w-full">
                    @if($resume)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="green" class="w-10 h-10">
                            <circle cx="12" cy="12" r="10" fill="green"/>
                            <path d="M10 15l-3-3 1.414-1.414L10 12.172l5.586-5.586L17 8l-7 7z" fill="white"/>
                        </svg>
                        <span class="text-green-500 text-sm pt-2" style="line-height: 30px">
                            Uploaded Resume
                        </span>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="text-neutral-400 w-10 h-10">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm3 14a1 1 0 011-1v-8a1 1 0 011-1h2a1 1 0 011 1v8a1 1 0 01-1 1zm4-2a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-neutral-400 text-sm pt-2" style="line-height: 30px">
                            Upload Resume
                        </span>
                    @endif
                </div>
            </label>
            
            <input wire:model="resume" class="hidden" type="file" accept=".pdf" name="resume" id="upload-resume">
            @error('resume')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>