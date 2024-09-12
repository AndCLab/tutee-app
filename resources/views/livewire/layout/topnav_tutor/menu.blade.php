{{-- Switch role  --}}
<x-wui-button sm wire:click='switchRole' flat primary icon='switch-vertical' spinner='switchRole' label='Switch Role' />

{{-- Icons --}}                
<x-wui-dropdown>
    <x-slot name="trigger">
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  
        fill="#292D32"  class="icon icon-tabler icons-tabler-filled icon-tabler-bookmarks">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 6a4 4 0 0 1 4 4v11a1 1 0 0 1 -1.514 .857l-4.486 -2.691l-4.486 2.691a1 1 0 0 1 -1.508 -.743l-.006 -.114v-11a4 4 0 0 1 4 -4h4z" />
            <path d="M16 2a4 4 0 0 1 4 4v11a1 1 0 0 1 -2 0v-11a2 2 0 0 0 -2 -2h-5a1 1 0 0 1 0 -2h5z" />
        </svg>
    </x-slot>

    <x-wui-dropdown.item>
        Your bookmarks
    </x-wui-dropdown.item>
</x-wui-dropdown>

<x-wui-dropdown>
    <x-slot name="trigger">
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24" 
        fill="#292D32"  class="icon icon-tabler icons-tabler-filled icon-tabler-message">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M18 3a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-4.724l-4.762 2.857a1 1 0 0 1 -1.508 -.743l-.006 -.114v-2h-1a4 4 0 0 1 -3.995 -3.8l-.005 -.2v-8a4 4 0 0 1 4 -4zm-4 9h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m2 -4h-8a1 1 0 1 0 0 2h8a1 1 0 0 0 0 -2" />
        </svg>
    </x-slot>

    <x-wui-dropdown.item>
        Your messages
    </x-wui-dropdown.item>
</x-wui-dropdown>


<x-wui-dropdown>
    <x-slot name="trigger">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="#292D32" class="icon icon-tabler icons-tabler-filled icon-tabler-bell">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
                d="M14.235 19c.865 0 1.322 1.024 .745 1.668a3.992 3.992 0 0 1 -2.98 1.332a3.992 3.992 0 0 1 -2.98 -1.332c-.552 -.616 -.158 -1.579 .634 -1.661l.11 -.006h4.471z" />
            <path
                d="M12 2c1.358 0 2.506 .903 2.875 2.141l.046 .171l.008 .043a8.013 8.013 0 0 1 4.024 6.069l.028 .287l.019 .289v2.931l.021 .136a3 3 0 0 0 1.143 1.847l.167 .117l.162 .099c.86 .487 .56 1.766 -.377 1.864l-.116 .006h-16c-1.028 0 -1.387 -1.364 -.493 -1.87a3 3 0 0 0 1.472 -2.063l.021 -.143l.001 -2.97a8 8 0 0 1 3.821 -6.454l.248 -.146l.01 -.043a3.003 3.003 0 0 1 2.562 -2.29l.182 -.017l.176 -.004z" />
        </svg>
    </x-slot>
    <!-- Display Notifications inside the dropdown -->
    <livewire:notifications />
</x-wui-dropdown>