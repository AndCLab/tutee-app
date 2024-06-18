<div class="my-5 w-2/3 mx-auto">
    @if (session('error-field'))
        <div class="w-full border border-blue-200 text-center py-3 rounded-lg antialiased bg-blue-100 text-blue-900 mb-2">
            {{ session('error-field') }}
        </div>
    @endif
    <div class="flex gap-2 pb-4 flex-wrap w-full">
        @if (!empty($selected))
            @foreach ($selected as $index => $select)
                <div class="bg-[#CBD5E1] text-[#0F172A] px-3 py-2 text-sm rounded-3xl flex items-center gap-2">
                    <p>
                        {{ $select }}
                    </p>
                    <svg wire:click='remove_field({{ $index }})' xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        class="size-4 cursor-pointer">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
            @endforeach
        @endif
    </div>
    <div class="h-[300px] overflow-y-scroll soft-scrollbar">
        <div class="flex flex-col">
            {{-- Loop the parent array --}}
            @foreach ($fields as $index => $field)
                <h1 class="text-xl font-semibold pb-2">{{ $index }}</h1>
                {{-- Looped the child array --}}
                <div class="flex gap-2 pb-4 flex-wrap">
                    @foreach ($field as $name)
                        <div @class([
                            'text-[#0F172A] px-3 py-2 text-sm rounded-3xl hover:bg-[#CBD5E1] transition',
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