<div class="md:w-3/4 mx-auto">
    <h1 class="text-[#0C3B2E] text-center text-3xl font-extrabold mb-10">Select your Fields</h1>
    {{-- <div class="grid grid-cols-3 text-[#0F172A] gap-4"> --}}
    <div class="text-[#0F172A] gap-4">
        {{-- <div class="space-y-2 border-r border-gray-200 text-sm">
            <div class="flex flex-col">
                <p class="font-bold">Certificates</p>
                @if ($certificate)
                    <a href="{{ $certificate->temporaryUrl() }}" target="_blank" class="text-blue-600 hover:underline">
                    Attached File </a>
                @endif
            </div>
            <div class="flex flex-col">
                <p class="font-bold">Resume</p>
                @if ($resume)
                    <a href="{{ $resume->temporaryUrl() }}" target="_blank" class="text-blue-600 hover:underline">
                    Attached File </a>
                @endif
            </div>
        </div> --}}
        {{-- <div class="col-span-2 space-y-5"> --}}
        <div class="space-y-5">
            <p class="text-sm text-gray-500 leading-relaxed text-center">
                Please specify your field tags based on the certificates and proof of education you submitted. These
                tags
                will be used when creating classes, so include as many relevant subjects as possible. Thank you!
            </p>
            <div class="flex flex-wrap gap-1 w-full">
                {{-- Selected fields --}}
                @if (!empty($selected))
                    @foreach ($selected as $index => $select)
                        <div wire:key='{{ $select }}'
                            class="bg-[#CBD5E1] text-[#0F172A] px-2 py-1 gap-2 text-sm rounded-3xl flex items-center">
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
            <form wire:submit='get_specific_field' class="space-y-2 mt-2">
                <div class="w-full">
                    <x-wui-input class="py-1.5" placeholder="Enter specific field" wire:model="specific" autofocus
                        autocomplete='off' shadowless/>
                </div>
                <div class="grid">
                    <x-white-button type='submit'>Add Field</x-white-button>
                </div>
            </form>
        </div>
    </div>
    <x-wui-notifications />
</div>
