<div class="md:w-3/4 w-full">
    <div class="md:grid md:grid-cols-4 pb-5">
        <p class="font-semibold pb-3 md:pb-0">Institute</p>
        <div class="md:col-span-3 space-y-3">
            @foreach ($inputs as $index => $input)
                <div @class([
                    'hidden' => count($inputs) === 1,
                    'block' => count($inputs) >= 1
                    ])>
                    <p class="font-medium text-sm">Institute {{ $index + 1 }}</p>
                </div>
                <div class="md:flex md:items-start md:gap-3 space-y-3 md:space-y-0">
                    <div class="space-y-3">
                        <div class="md:inline-flex w-full gap-2 space-y-3 md:space-y-0">
                            {{-- From --}}
                            <x-wui-select wire:model="from.{{ $index }}" placeholder="From" :async-data="route('dates')"
                                option-label="year" option-value="id" autocomplete="off" />

                            {{-- To --}}
                            <x-wui-select wire:model="to.{{ $index }}" placeholder="To" :async-data="route('dates')"
                                option-label="year" option-value="id" autocomplete="off" />
                        </div>

                        <x-wui-input class="w-full" id="institute.{{ $index }}"
                            name="institute.{{ $index }}" placeholder="Institute"
                            wire:model='institute.{{ $index }}' />
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
                autocomplete="off">
                <x-wui-select.option label="High School" value="highschool" />
                <x-wui-select.option label="College" value="college" />
            </x-wui-select>
        </div>
    </div>

    @if (session('error-institute'))
        {{ session('error-institute') }}
    @endif
</div>
