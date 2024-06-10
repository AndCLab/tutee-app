<div>
    <x-wui-select label="School Level" placeholder="Select school level" wire:model.defer="grade_level">
        <x-wui-select.option label="High School" value="highschool" />
        <x-wui-select.option label="College" value="college" />
    </x-wui-select>
</div>

<div>
    @foreach ($inputs as $index => $input)
        <div>
            <x-wui-select label="From" placeholder="1990" wire:model.defer="from.{{ $index }}" id="from.{{ $index }}" name="from.{{ $index }}">
                @foreach ($dates as $year)
                    <x-wui-select.option label="{{ $year }}" value="{{ $year }}-01-01" />
                @endforeach
            </x-wui-select>
            <x-wui-select label="To" placeholder="2000" wire:model.defer="to.{{ $index }}" id="to.{{ $index }}" name="to.{{ $index }}">
                @foreach ($dates as $year)
                    <x-wui-select.option label="{{ $year }}" value="{{ $year }}-01-01" />
                @endforeach
            </x-wui-select>
        </div>

        <div>
            <x-wui-input label="Institute" id="institute.{{ $index }}" name="institute.{{ $index }}"
                placeholder="Institute" wire:model='institute.{{ $index }}' />

        </div>
        <x-wui-button wire:click='remove_institute({{ $index }})' emerald label="Remove" />
    @endforeach
    <x-wui-button wire:click='add_institute' wire:loading.attr='@disabled(true)'
        wire:target='add_institute' emerald label="Add Insitute" />
</div>

@if (session('error-institute'))
    {{ session('error-institute') }}
@endif
