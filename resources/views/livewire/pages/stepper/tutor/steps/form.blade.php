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
                            <x-wui-select placeholder="From" wire:model="from.{{ $index }}" errorless shadowless :searchable='false'>
                                @foreach ($dates as $year)
                                    <x-wui-select.option label="{{ $year }}" value="{{ $year }}-01-01"/>
                                @endforeach
                            </x-wui-select>

                            {{-- To --}}
                            <x-wui-select placeholder="To" wire:model="to.{{ $index }}" errorless shadowless :searchable='false'>
                                @foreach ($dates as $year)
                                    <x-wui-select.option label="{{ $year }}" value="{{ $year }}-01-01"/>
                                @endforeach
                            </x-wui-select>
                        </div>

                        {{-- Input Work Experience --}}
                        <x-wui-input class="w-full" id="work.{{ $index }}" name="work.{{ $index }}"
                            placeholder="Work Experience" wire:model='work.{{ $index }}' errorless shadowless/>

                        {{-- Input Work Experience --}}
                        <x-wui-input class="w-full" id="company.{{ $index }}" name="company.{{ $index }}"
                            placeholder="Company (Optional)" wire:model='company.{{ $index }}' errorless shadowless/>
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

    <div class="md:grid md:grid-cols-4 mt-4">
        <p class="font-semibold pb-3 md:pb-0">Degree</p>
        <div class="md:col-span-3">
            @foreach ($input_degree as $index => $input)
                <div @class([
                    'hidden' => count($input_degree) === 1,
                    'block' => count($input_degree) >= 1
                ])>
                    <p class="font-medium text-sm pb-3">Degree {{ $index + 1 }}</p>
                </div>
                <div class="md:flex md:items-center md:gap-3 space-y-3 md:space-y-0 pb-3">
                    <div class="space-y-3 w-full">
                        {{-- Input Degree --}}
                        <div class="relative" x-data="autocomplete({{ $index }})">
                            <x-wui-input
                                name="degree.{{ $index }}"
                                wire:model='degree.{{ $index }}'
                                placeholder="Degree"
                                x-ref="input"
                                x-on:input="filterSuggestions"
                                errorless
                                shadowless
                            />

                            <!-- Suggestions Dropdown -->
                            <div x-show="isOpen"
                                @click.away="close"
                                class="absolute mt-1 z-10 w-full max-h-52 overflow-auto soft-scrollbar bg-white border border-gray-200 rounded-md shadow-lg"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">
                                <ul>
                                    <template x-for="(suggestion, index) in filteredSuggestions" :key="index">
                                        <li class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                            x-text="suggestion"
                                            @click="selectSuggestion(suggestion)">
                                        </li>
                                    </template>
                                    <li x-show="filteredSuggestions.length === 0" class="px-4 py-2 text-gray-500">
                                        No results found
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div @class([
                        'hidden' => count($input_degree) === 1,
                        'block' => count($input_degree) >= 1
                    ])>
                        <div>
                            {{-- Remove Work --}}
                            <div class="hidden md:block">
                                <x-wui-button.circle negative flat sm wire:click='remove_degree({{ $index }})' icon="x" />
                            </div>
                            <x-danger-button wire:click='remove_degree({{ $index }})'
                                class="md:hidden block w-full">Remove Degree</x-danger-button>
                        </div>
                    </div>
                </div>
            @endforeach
            {{-- Add Work --}}
            @if (count($input_degree) !== 3)
                <x-wui-button xs spinner='add_degree' wire:click='add_degree' flat secondary label="Add Degree" icon='plus-sm' />
            @endif
        </div>
    </div>

    <div class="mt-4">
        {{-- Upload Certificates --}}
        <div class="md:grid md:grid-cols-4">
            <p class="font-semibold pb-2 md:pb-0">Certificates</p>
            <div class="col-span-3 mb-5">
                @foreach ($input_certi as $index => $input)
                    <div @class([
                        'hidden' => count($input_certi) === 1,
                        'block' => count($input_certi) >= 1
                    ]) wire:key="{{ $index }}">
                        <p class="font-medium text-sm pb-3">Certificate {{ $index + 1 }}</p>
                    </div>

                    <div class="md:flex md:items-start md:gap-3 space-y-3 md:space-y-0 pb-3">
                        <div class="space-y-3">
                            <div class="md:inline-flex gap-2 space-y-3 md:space-y-0">
                                {{-- Title --}}
                                <div class="w-full">
                                    <x-wui-input
                                        name="title_certi.{{ $index }}"
                                        wire:model='title_certi.{{ $index }}'
                                        placeholder="Title"
                                        errorless
                                        shadowless
                                    />
                                </div>

                                {{-- From --}}
                                <x-wui-select placeholder="From" wire:model="from_certi.{{ $index }}" errorless shadowless :searchable='false'>
                                    @foreach ($dates as $year)
                                        <x-wui-select.option label="{{ $year }}" value="{{ $year }}-01-01"/>
                                    @endforeach
                                </x-wui-select>
                            </div>

                            {{-- Input Certificate --}}
                            <x-wui-input wire:model="certificates.{{ $index }}" type="file" accept=".pdf,.png,.jpg,.jpeg"
                                class="p-0 text-gray-500 font-medium text-sm border-none shadow-none bg-gray-100 file:cursor-pointer cursor-pointer file:border-0 file:py-2 file:px-4 file:mr-4 file:bg-[#0F172A] file:hover:bg-[#0F172A]/90 file:text-white rounded"
                                errorless />
                        </div>
                        <div>
                            <div @class([
                                    'hidden' => count($input_certi) === 1,
                                    'block' => count($input_certi) >= 1
                                ])>
                                <x-wui-button.circle negative flat sm wire:click='remove_cert({{ $index }})' icon="x" />
                            </div>
                        </div>
                    </div>
                @endforeach
                @if (count($input_certi) !== 3)
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
                            <div wire:loading.class='hidden' wire:target="resume">
                                @if (!$resume)
                                    Upload Resume
                                @else
                                    Uploaded!
                                @endif
                            </div>

                            <div wire:loading wire:target="resume">
                                Uploading...
                            </div>
                        </span>
                        <p class="text-xs font-light text-gray-400">Only PDF is allowed.</p>
                    </div>
                </label>
                <input wire:model="resume" class="hidden" type="file" accept=".pdf" name="resume" id="upload-resume">
            </div>
        </div>
    </div>

    <x-wui-notifications />
    <x-wui-errors />

    <script>
        function autocomplete(index) {
            return {
                isOpen: false,
                query: '',
                suggestions: [
                    'Associate of Arts (AA)', 'Associate of Science (AS)', 'Associate of Applied Science (AAS)', 'Associate of Fine Arts (AFA)',
                    'Associate of Business Administration (ABA)', 'Associate of General Studies (AGS)', 'Associate of Engineering (AE)',

                    'Bachelor of Arts (BA)', 'Bachelor of Science (BS)', 'Bachelor of Fine Arts (BFA)', 'Bachelor of Business Administration (BBA)',
                    'Bachelor of Engineering (BEng)', 'Bachelor of Technology (BTech)', 'Bachelor of Applied Science (BAS)', 'Bachelor of Architecture (BArch)',
                    'Bachelor of Computer Science (BCS)', 'Bachelor of Design (BDes)', 'Bachelor of Environmental Science (BES)', 'Bachelor of Information Technology (BIT)',
                    'Bachelor of Music (BM)', 'Bachelor of Social Work (BSW)', 'Bachelor of Nursing (BN)', 'Bachelor of Public Health (BPH)', 'Bachelor of Laws (LLB)',

                    'Master of Arts (MA)', 'Master of Science (MS)', 'Master of Fine Arts (MFA)', 'Master of Business Administration (MBA)',
                    'Master of Public Administration (MPA)', 'Master of Public Health (MPH)', 'Master of Social Work (MSW)', 'Master of Laws (LLM)',
                    'Master of Education (MEd)', 'Master of Engineering (MEng)', 'Master of Architecture (MArch)', 'Master of Music (MM)',
                    'Master of Nursing (MN)', 'Master of Information Technology (MIT)', 'Master of Design (MDes)', 'Master of Urban Planning (MUP)',
                    'Master of Healthcare Administration (MHA)', 'Master of Library Science (MLS)', 'Master of Public Policy (MPP)', 'Master of Environmental Science (MES)',

                    'Doctor of Philosophy (PhD)', 'Doctor of Education (EdD)', 'Doctor of Business Administration (DBA)', 'Doctor of Public Health (DrPH)',
                    'Doctor of Medicine (MD)', 'Doctor of Osteopathic Medicine (DO)', 'Doctor of Dental Surgery (DDS)', 'Doctor of Dental Medicine (DMD)',
                    'Doctor of Veterinary Medicine (DVM)', 'Doctor of Pharmacy (PharmD)', 'Doctor of Nursing Practice (DNP)', 'Doctor of Physical Therapy (DPT)',
                    'Doctor of Optometry (OD)', 'Doctor of Chiropractic (DC)', 'Doctor of Podiatric Medicine (DPM)', 'Doctor of Public Administration (DPA)',

                    'Juris Doctor (JD)', 'Doctor of Judicial Science (SJD)', 'Executive Master of Business Administration (EMBA)',

                    'Certificate of Advanced Study (CAS)', 'Post-Master’s Certificate', 'Graduate Certificate', 'Professional Certificate'
                ],

                filteredSuggestions: [],

                filterSuggestions() {
                    this.query = this.$refs.input.value;
                    this.filteredSuggestions = this.suggestions
                        .filter(suggestion => suggestion.toLowerCase().includes(this.query.toLowerCase()))
                        .sort((a, b) => {
                            const queryLower = this.query.toLowerCase();
                            const aStartsWith = a.toLowerCase().startsWith(queryLower);
                            const bStartsWith = b.toLowerCase().startsWith(queryLower);

                            /*
                                if (aStartsWith && !bStartsWith) {
                                    return -1;
                                }
                                if (!aStartsWith && bStartsWith) {
                                    return 1;
                                }
                                return 0;
                            */

                            return aStartsWith && !bStartsWith ? -1 : bStartsWith && !aStartsWith ? 1 : 0;
                        });

                    this.isOpen = this.filteredSuggestions.length > 0;
                },

                selectSuggestion(suggestion) {
                    this.query = suggestion;
                    this.$refs.input.value = suggestion;
                    this.isOpen = false;

                    @this.set('degree.' + index, suggestion, true);
                },

                close() {
                    this.isOpen = false;
                }
            };
        }
    </script>
</div>
