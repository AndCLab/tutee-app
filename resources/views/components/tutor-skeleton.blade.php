@props(['loadClass' => null])

@if ($loadClass != null)
    <div class="w-full space-y-4" wire:loading wire:target='{{ $loadClass }}'>

    </div>
@endif
