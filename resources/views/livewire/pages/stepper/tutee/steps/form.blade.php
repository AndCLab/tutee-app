<div class="flex flex-col justify-center items-center w-full py-3 sm:py-5 gap-2">
    <div class="grid sm:grid-cols-4 sm:w-2/3 w-full">
        <p class="font-semibold sm:py-3">Institute</p>
        <div class="col-span-3">
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

                <x-wui-input class="w-full mb-3" id="institute.{{ $index }}"
                    name="institute.{{ $index }}" placeholder="Institute"
                    wire:model='institute.{{ $index }}' />
                {{-- Remove Institute --}}
                <x-wui-button class="w-full" wire:click='remove_institute({{ $index }})' negative label="Remove" />
            @endforeach
            {{-- Add Insitute --}}
            <x-wui-button class="w-full mt-2" wire:click='add_institute' emerald label="Add Insitute" />
        </div>
    </div>
    <div class="grid sm:grid-cols-4 sm:w-2/3 w-full">
        <p class="font-semibold sm:py-3">School Grade</p>
        <div class="sm:col-span-3 pt-3">
            <x-wui-select class="w-full" placeholder="Select school level" wire:model.defer="grade_level">
                <x-wui-select.option label="High School" value="highschool" />
                <x-wui-select.option label="College" value="college" />
            </x-wui-select>
        </div>

    </div>

    @if (session('error-institute'))
        {{ session('error-institute') }}
    @endif
</div>
