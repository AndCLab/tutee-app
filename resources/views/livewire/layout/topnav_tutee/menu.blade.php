{{-- Icons --}}
@php
    use App\Models\Bookmark;
@endphp

{{-- Bookmark Dropdown --}}
<x-wui-dropdown>
    <x-slot name="trigger">
        <livewire:bookmark-icon />
    </x-slot>

    <!-- Display Bookmarks inside the dropdown -->
    <livewire:bookmarks />
</x-wui-dropdown>


<x-wui-dropdown>
    <x-slot name="trigger">
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"
        fill="#292D32"  class="icon icon-tabler icons-tabler-filled icon-tabler-message">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M18 3a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-4.724l-4.762 2.857a1 1 0 0 1 -1.508 -.743l-.006 -.114v-2h-1a4 4 0 0 1 -3.995 -3.8l-.005 -.2v-8a4 4 0 0 1 4 -4zm-4 9h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m2 -4h-8a1 1 0 1 0 0 2h8a1 1 0 0 0 0 -2" />
        </svg>
    </x-slot>

    <livewire:messages />
</x-wui-dropdown>


{{-- Notification Dropdown --}}
<x-wui-dropdown>
    <x-slot name="trigger">
        <livewire:notification-icon />
    </x-slot>

    <!-- Display Notifications inside the dropdown -->
    <livewire:notifications />
</x-wui-dropdown>
