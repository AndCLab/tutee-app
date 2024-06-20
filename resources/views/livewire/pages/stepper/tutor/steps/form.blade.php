<div class="flex flex-col justify-center items-center w-full py-3 sm:py-5 gap-2">
    <div class="grid sm:grid-cols-4 sm:w-2/3 w-full">
        <p class="font-semibold sm:py-3">Work Experience</p>
        <div class="col-span-3 mb-5">
            @foreach ($inputs as $index => $input)
                <div class="sm:flex w-full gap-2 py-3">
                    <x-wui-select class="w-full" placeholder="From" wire:model.defer="from.{{ $index }}"
                        id="from.{{ $index }}" name="from.{{ $index }}">
                        @foreach ($dates as $year)
                            <x-wui-select.option label="{{ $year }}" value="{{ $year }}-01-01" />
                        @endforeach
                    </x-wui-select>
                    <x-wui-select class="w-full" placeholder="To" wire:model.defer="to.{{ $index }}"
                        id="to.{{ $index }}" name="to.{{ $index }}">
                        @foreach ($dates as $year)
                            <x-wui-select.option label="{{ $year }}" value="{{ $year }}-01-01" />
                        @endforeach
                    </x-wui-select>
                </div>
                {{-- Input Work Experience --}}
                <x-wui-input class="w-full mb-3" id="work.{{ $index }}"
                    name="work.{{ $index }}" placeholder="Work Experience"
                    wire:model='work.{{ $index }}' />
                {{-- Remove Work --}}
                <x-wui-button class="w-full" wire:click='remove_work({{ $index }})' negative label="Remove" />
            @endforeach
            {{-- Add Work --}}
            <x-wui-button class="w-full mt-2" wire:click='add_work' emerald label="Add Work Experience" />
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