@php
    $dates = [];
    for ($year = 1990; $year <= 2024; $year++) {
        $dates[] = $year;
    }
@endphp

<div class="md:w-3/4 w-full">
    <div class="md:grid md:grid-cols-4">
        <p class="font-semibold pb-3 md:pb-0">Experience</p>
        <div class="md:col-span-3">
            @foreach ($input_work as $index => $input)
            <div @class([
                'hidden' => count($input_work) === 1,
                'block' => count($input_work) >= 1
            ])>
                <p class="font-medium text-sm pb-3">Work Experience {{ $index + 1 }}</p>
            </div>
            <div class="md:flex md:items-start md:gap-3 space-y-3 md:space-y-0 pb-3">
                    <div class="space-y-3">
                        <div class="md:inline-flex w-full gap-2 space-y-3 md:space-y-0">
                            {{-- From --}}
                            <x-wui-select placeholder="From" wire:model="from.{{ $index }}" errorless shadowless>
                                @foreach ($dates as $year)
                                    <x-wui-select.option label="{{ $year }}" value="{{ $year }}-01-01" :searchable='false'/>
                                @endforeach
                            </x-wui-select>

                            {{-- To --}}
                            <x-wui-select placeholder="To" wire:model="to.{{ $index }}" errorless shadowless>
                                @foreach ($dates as $year)
                                    <x-wui-select.option label="{{ $year }}" value="{{ $year }}-01-01" :searchable='false'/>
                                @endforeach
                            </x-wui-select>
                        </div>

                        {{-- Input Work Experience --}}
                        <x-wui-input class="w-full" id="work.{{ $index }}" name="work.{{ $index }}"
                            placeholder="Work Experience" wire:model='work.{{ $index }}' errorless shadowless/>
                    </div>
                    <div>
                        {{-- Remove Work --}}
                        <div class="hidden md:block">
                            <x-wui-button.circle negative flat sm wire:click='remove_work({{ $index }})' icon="x" />
                        </div>
                        <x-danger-button wire:click='remove_work({{ $index }})'
                            class="md:hidden block w-full">Remove
                            Work Experience</x-danger-button>
                    </div>
                </div>
            @endforeach
            {{-- Add Work --}}
            @if (count($input_work) !== 3)
                <x-wui-button xs spinner='add_work' wire:click='add_work' flat secondary label="Add Work Experience" icon='plus-sm' />
            @endif
        </div>
    </div>

    <div class="mt-4">
        {{-- Upload Certificates --}}
        <div class="md:grid md:grid-cols-4">
            <p class="font-semibold pb-2 md:pb-0">Certificates</p>
            <div class="col-span-3 mb-5">
                @foreach ($certificates as $index => $input)
                    <div @class([
                        'hidden' => count($certificates) === 1,
                        'block' => count($certificates) >= 1
                        ])
                        wire:key="{{ $index }}">
                        <p class="font-medium text-sm pb-3">Certificate {{ $index + 1 }}</p>
                    </div>
                    <div class="flex gap-x-3 items-center pb-3">
                        <div class="w-full">
                            <x-wui-input wire:model="certificates.{{ $index }}" type="file" accept=".pdf,.png,.jpg,.jpeg"
                            class="p-0 text-gray-500 font-medium text-sm border-none shadow-none bg-gray-100 file:cursor-pointer cursor-pointer file:border-0 file:py-2 file:px-4 file:mr-4 file:bg-[#0F172A] file:hover:bg-[#0F172A]/90 file:text-white rounded"
                            errorless />
                        </div>
                        <div @class([
                                'hidden' => count($certificates) === 1,
                                'block' => count($certificates) >= 1
                            ])>
                            <x-wui-button.circle negative flat sm wire:click='remove_cert({{ $index }})' icon="x" />
                        </div>
                    </div>
                @endforeach
                @if (count($certificates) !== 3)
                    <x-wui-button xs spinner='add_cert' wire:click='add_cert' flat secondary label="Add Certificate" icon='plus-sm' />
                @endif
            </div>
        </div>

        {{-- Upload Resume --}}
        <div class="md:grid md:grid-cols-4">
            <p class="font-semibold pb-2 md:pb-0">Resume</p>
            <div class="col-span-3 mb-5">
                <label for="upload-resume" @class([
                    'rounded-md cursor-pointer h-fit w-full flex bg-white',
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
                                fill="currentColor"
                                class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check">
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
                        <p class="text-xs font-light text-gray-400">Only PDF is allowed.</p>
                    </div>
                </label>
                <input wire:model="resume" class="hidden" type="file" accept=".pdf" name="resume"
                    id="upload-resume">

            </div>
        </div>
    </div>

    <x-wui-notifications />

    <x-wui-errors />
</div>
