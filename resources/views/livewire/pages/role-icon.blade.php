<div class="relative" wire:poll.5s>
    @if($otherUnreadCount > 0)
        <span class="absolute top-[-0.25rem] left-0 inline-block text-center leading-none text-white bg-red-600 rounded-full py-1 px-1 text-xs">
            {{ $otherUnreadCount }}
        </span>
    @endif
</div>
