<div class="icon-container flex items-center space-x-2">
    {{-- Icons --}}

    {{-- Bookmark Dropdown --}}
    <x-wui-dropdown >
        <x-slot name="trigger">
            <livewire:bookmark-icon />
        </x-slot>

        <!-- Display Bookmarks inside the dropdown -->
        <livewire:bookmarks />
    </x-wui-dropdown>


    <x-wui-dropdown >

        <x-slot name="trigger">
            <livewire:message-icon />
        </x-slot>

        <livewire:messages />
    </x-wui-dropdown>


    {{-- Notification Dropdown --}}
    <x-wui-dropdown >
        <x-slot name="trigger">
            <livewire:notification-icon />
        </x-slot>

        <!-- Display Notifications inside the dropdown -->
        <livewire:notifications />
    </x-wui-dropdown>
</div>
