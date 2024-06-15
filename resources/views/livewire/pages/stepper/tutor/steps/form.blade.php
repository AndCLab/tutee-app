<div class="flex flex-col justify-center items-center w-full py-3 sm:py-5 gap-2">
    <div class="grid sm:grid-cols-4 sm:w-2/3 w-full">
        <p class="font-semibold sm:py-3">Work Experience</p>
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
    </div>

</div>