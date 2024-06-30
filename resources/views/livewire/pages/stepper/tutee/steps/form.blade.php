<div class="md:w-3/4 w-full">
    <div class="md:grid md:grid-cols-4 pb-5">
        <p class="font-semibold pb-3 md:pb-0">Institute</p>
        <div class="md:col-span-3 space-y-3">
            @foreach ($inputs as $index => $input)
                <div class="md:flex md:items-start md:gap-3 space-y-3 md:space-y-0">
                    <div class="space-y-3">
                        <div class="md:inline-flex w-full gap-2 space-y-3 md:space-y-0">
                            {{-- From --}}
                            <x-wui-select wire:model.live="from.{{ $index }}" placeholder="From" :async-data="route('dates')"
                                option-label="year" option-value="id" autocomplete="off" />

                            {{-- To --}}
                            <x-wui-select wire:model.live="to.{{ $index }}" placeholder="To" :async-data="route('dates')"
                                option-label="year" option-value="id" autocomplete="off" />
                        </div>

                        <x-wui-input class="w-full" id="institute.{{ $index }}"
                            name="institute.{{ $index }}" placeholder="Institute"
                            wire:model='institute.{{ $index }}' />
                    </div>
                    {{-- Remove Institute --}}
                    <div class="hidden md:block">
                        <x-delete-icon wire:click='remove_institute({{ $index }})'>
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
                    <x-danger-button wire:click='remove_institute({{ $index }})'
                        class="md:hidden block w-full">Remove Institute</x-danger-button>
                </div>
            @endforeach
            {{-- <x-wui-errors /> --}}

            {{-- Add Insitute --}}
            <x-white-button class="w-full" wire:click='add_institute' emerald label="Add Insitute">Add
                Insitute</x-white-button>
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
