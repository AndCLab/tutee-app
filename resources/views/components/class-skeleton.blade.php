@props(['loadClass' => null])

@if ($loadClass != null)
    <div class="w-full space-y-4" wire:loading wire:target='{{ $loadClass }}'>
        <div class="animate-pulse w-full space-y-4 rounded-md bg-[#F1F5F9] p-4">
            <div class="space-y-1">
            <div class="flex items-center justify-between">
                <div class="h-6 w-60 bg-[#D4E0EC] rounded-md"></div>
                <div class="h-6 w-2 bg-[#D4E0EC] rounded-md"></div>
            </div>
            <div class="flex items-center gap-2">
                <div class="h-4 w-6 bg-[#D4E0EC] rounded-md"></div>
                <p></p>
            </div>
            </div>
            <div class="flex flex-col gap-1">
            <div class="h-3 w-full bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-5/6 bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-3/4 bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-2/4 bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-1/4 bg-[#D4E0EC] rounded-md"></div>
            </div>
        </div>
        <div class="animate-pulse w-full space-y-4 rounded-md bg-[#F1F5F9] p-4">
            <div class="space-y-1">
            <div class="flex items-center justify-between">
                <div class="h-6 w-60 bg-[#D4E0EC] rounded-md"></div>
                <div class="h-6 w-2 bg-[#D4E0EC] rounded-md"></div>
            </div>
            <div class="flex items-center gap-2">
                <div class="h-4 w-6 bg-[#D4E0EC] rounded-md"></div>
                <p></p>
            </div>
            </div>
            <div class="flex flex-col gap-1">
            <div class="h-3 w-full bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-5/6 bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-3/4 bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-2/4 bg-[#D4E0EC] rounded-md"></div>
            <div class="h-3 w-1/4 bg-[#D4E0EC] rounded-md"></div>
            </div>
        </div>
    </div>
@endif
