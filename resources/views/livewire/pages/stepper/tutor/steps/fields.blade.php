<div class="my-5 w-3/4 mx-auto">
    @if (session('error-field'))
        <div
            class="w-full border border-blue-200 text-center py-3 rounded-lg antialiased bg-blue-100 text-blue-900 mb-2">
            {{ session('error-field') }}
        </div>
    @endif

    <h1 class="text-[#0C3B2E] text-center text-3xl font-extrabold mb-10">User Profile Overview</h1>
    <div class="grid grid-cols-3 text-[#0F172A] gap-4">
        <div class="space-y-2 border-r border-gray-200">
            <div class="flex flex-col">
                <p class="font-bold">Work Experience</p>
                <p>Attached File</p>
            </div>
            <div class="flex flex-col">
                <p class="font-bold">Certificates</p>
                <p>Attached File</p>
            </div>
            <div class="flex flex-col">
                <p class="font-bold">Resume</p>
                <p>Attached File</p>
            </div>
        </div>
        <div class="col-span-2 space-y-5">
            <p class="text-sm text-gray-500 leading-relaxed text-center">
                Please specify your field tags based on the certificates and proof of education you submitted. These
                tags
                will be used when creating classes, so include as many relevant subjects as possible. Thank you!
            </p>
            <div class="flex flex-wrap gap-2 w-full">
                {{-- Selected fields --}}
                @if (!empty($selected))
                    @foreach ($selected as $index => $select)
                        <div wire:key='{{ $select }}'
                            class="bg-[#CBD5E1] text-[#0F172A] px-3 py-2 text-sm rounded-3xl flex items-center">
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
            <form wire:submit='get_specific_field()' class="space-y-2 mt-2">
                <div class="w-full">
                    <x-wui-input class="py-1.5" placeholder="Enter specific field" wire:model="specific"
                        wire:loading.attr='disabled' wire:loading.class='bg-gray-100 transition' autofocus
                        autocomplete='off' />
                </div>
                <div class="grid">
                    <x-white-button type='submit'>Add Field</x-white-button>
                </div>
            </form>
        </div>
    </div>




</div>
