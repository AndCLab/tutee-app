<div class="md:w-3/4 w-full">
    <h1 class="text-[#0C3B2E] text-center text-2xl md:text-3xl font-extrabold mb-3">Select your Fields</h1>

    {{-- Selected fields --}}
    <div class="flex gap-2 pb-4 flex-wrap w-full col-span-2">
        @if (!empty($selected))
            @foreach ($selected as $index => $select)
                <div wire:key='{{ $select }}' class="bg-[#CBD5E1] text-[#0F172A] px-2 py-1 text-sm rounded-3xl flex items-center gap-1">
                    <p>
                        {{ $select }}
                    </p>
                    <svg wire:click='remove_field({{ $index }})' xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 cursor-pointer">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
            @endforeach
        @endif
    </div>

    <div class="grid md:grid-cols-5 gap-3">
        <div class="md:col-span-3">
            {{-- Populated fields --}}
            <div class="h-fit md:h-[300px] scroll-smooth pr-2 md:overflow-y-scroll soft-scrollbar">
                <div class="flex flex-col">
                    {{-- Loop the parent array --}}
                    @foreach ($fields as $index => $field)
                        <h1 class="text-xl font-semibold pb-2">{{ $index }}</h1>
                        {{-- Looped the child array --}}
                        <div class="flex gap-2 pb-4 flex-wrap">
                            @foreach ($field as $name)
                                <div wire:key='{{ $name }}' @class([
                                    'text-[#0F172A] px-2 py-1 text-sm rounded-3xl hover:bg-[#CBD5E1] transition',
                                    'bg-[#CBD5E1] cursor-default' => in_array($name, $selected),
                                    'bg-[#F1F5F9] cursor-pointer' => !in_array($name, $selected),
                                ]) wire:click='get_field("{{ $name }}")'>
                                    {{ $name }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="md:col-span-2">
            {{-- Add specific field --}}
            <form wire:submit='get_specific_field' class="space-y-2 mt-2">
                <div class="w-full">
                    <x-wui-input class="py-1.5" label="Specific Field" placeholder="Enter specific field"
                        wire:model="specific" wire:loading.attr='disabled' wire:loading.class='bg-gray-100 transition'
                        autofocus autocomplete='off' />
                </div>
                <div class="grid">
                    <x-white-button type='submit'>Add Field</x-white-button>
                </div>
            </form>
        </div>
    </div>
    <x-wui-notifications />
</div>
