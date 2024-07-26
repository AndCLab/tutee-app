@props(['wireTarget' => null])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'items-center px-4 py-2 disabled:bg-red-400 bg-red-600 border border-transparent rounded text-sm text-white hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    <div class="inline-flex items-center gap-2">
        <p>
            {{ $slot }}
        </p>
        @if ($wireTarget !== null)
            <div role="status" wire:loading wire:target='{{ $wireTarget }}'>
                <svg class="animate-spin size-4 shrink-0"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        @endif
    </div>
</button>
