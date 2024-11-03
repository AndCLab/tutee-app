<div class="icon-container flex items-center space-x-2">
    {{-- Icons --}}

    {{-- Bookmark Dropdown --}}
    <x-wui-dropdown width="7xl">
        <x-slot name="trigger" >
            <div class="text-black hover:text-gray-600 transform hover:scale-110 hover:shadow-lg transition duration-150 ease-in-out">
                <livewire:bookmark-icon />
            </div>
        </x-slot>



        <!-- Display Bookmarks inside the dropdown -->
        <livewire:bookmarks />
    </x-wui-dropdown>


    <x-wui-dropdown width="7xl">

        <x-slot name="trigger" >
            <div class="text-black hover:text-gray-600 transform hover:scale-110 hover:shadow-lg transition duration-150 ease-in-out">
                <livewire:message-icon />
            </div>

        </x-slot>

        <livewire:messages />
    </x-wui-dropdown>


    {{-- Notification Dropdown --}}
    <x-wui-dropdown width="7xl">
        <x-slot name="trigger" >
            <div class="text-black hover:text-gray-600 transform hover:scale-110 hover:shadow-lg transition duration-150 ease-in-out">
                <livewire:notification-icon />
            </div>

        </x-slot>

        <!-- Display Notifications inside the dropdown -->
        <livewire:notifications />
    </x-wui-dropdown>
</div>
