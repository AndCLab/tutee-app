@php
    $dates = [];
    for ($year = 1990; $year <= 2024; $year++) {
        $dates[] = $year;
    }
@endphp

<div class="md:w-3/4 w-full">
    <div class="md:grid md:grid-cols-4 pb-5">
        <p class="font-semibold pb-3 md:pb-0">Institute</p>
        <div class="md:col-span-3">
            @foreach ($inputs as $index => $input)
                <div @class([
                    'hidden' => count($inputs) === 1,
                    'block' => count($inputs) >= 1
                    ])>
                    <p class="font-medium text-sm pb-3">Institute {{ $index + 1 }}</p>
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

                        <div class="relative" x-data="autocomplete({{ $index }})">
                            <x-wui-input
                                class="w-full"
                                name="institute.{{ $index }}"
                                wire:model='institute.{{ $index }}'
                                placeholder="Institute"
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
                    {{-- Remove Institute --}}
                    <div class="hidden md:block">
                        <x-wui-button.circle negative flat sm wire:click='remove_institute({{ $index }})' icon="x" />
                    </div>
                    <x-danger-button wire:click='remove_institute({{ $index }})'
                        class="md:hidden block w-full">Remove Institute</x-danger-button>
                </div>
            @endforeach
            {{-- <x-wui-errors /> --}}

            {{-- Add Insitute --}}
            @if (count($inputs) !== 3)
                <x-wui-button xs spinner='add_institute' wire:click='add_institute' flat secondary label="Add Insitute" icon='plus-sm' />
            @endif
        </div>
    </div>
    <div class="grid md:grid-cols-4 pb-4">
        <p class="font-semibold pb-3 md:pb">School Grade</p>
        <div class="md:col-span-3">
            <x-wui-select class="w-full" placeholder="Select school level" wire:model.live="grade_level"
                autocomplete="off" errorless shadowless>
                @foreach ($gradeLevelList as $item)
                    <x-wui-select.option label="{{ $item }}" value="{{ $item }}"/>
                @endforeach
                {{-- <x-wui-select.option label="High School" value="highschool" />
                <x-wui-select.option label="College" value="college" /> --}}
            </x-wui-select>
        </div>
    </div>

    <x-wui-errors />

    <script>
        function autocomplete(index) {
            return {
                isOpen: false,
                query: '',
                suggestions: [
                    'University of the Philippines Diliman', 'Ateneo de Manila University', 'De La Salle University',
                    'University of Santo Tomas', 'Mapua University', 'Far Eastern University', 'University of the East',
                    'Polytechnic University of the Philippines', 'Adamson University',
                    'Technological Institute of the Philippines', 'Pamantasan ng Lungsod ng Maynila',
                    'Lyceum of the Philippines University', 'Centro Escolar University',
                    'San Beda University', 'National University', 'Miriam College', 'St. Scholastica\'s College',
                    'Arellano University', 'University of Asia and the Pacific', 'University of Cebu',
                    'Holy Angel University', 'University of San Carlos', 'University of San Jose-Recoletos',
                    'Cebu Doctors\' University', 'University of Mindanao', 'Xavier University – Ateneo de Cagayan',
                    'Ateneo de Davao University', 'Mindanao State University – Iligan Institute of Technology', 'Central Philippine University',
                    'Silliman University', 'West Visayas State University', 'University of Southeastern Philippines',
                    'Ateneo de Zamboanga University', 'Mindanao State University – Marawi Campus',
                    'Saint Louis University', 'University of Baguio', 'University of Saint Louis – Tuguegarao',
                    'Nueva Ecija University of Science and Technology', 'Bulacan State University',
                    'Batangas State University', 'Cavite State University',
                    'Bicol University', 'Tarlac State University',
                    'Pangasinan State University', 'Western Mindanao State University', 'Visayas State University',
                    'University of Negros Occidental – Recoletos',
                    'Aklan State University', 'University of the Immaculate Conception',
                    'Jose Rizal University', 'Philippine Normal University'
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

                    @this.set('institute.' + index, suggestion, true);
                },

                close() {
                    this.isOpen = false;
                }
            };
        }
    </script>
</div>
