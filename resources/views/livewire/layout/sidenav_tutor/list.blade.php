{{-- Discover --}}
<li x-data="{ tooltip: false }" class="relative">
    <a href="{{ route('tutor.discover') }}" wire:navigate x-on:mouseenter="tooltip = !tooltip"
        x-on:mouseleave="tooltip = false" :class="expanded ? 'w-fit' : 'w-full'"
        class="inline-flex items-center gap-3 text-sm font-medium hover:bg-[#F2F2F2]/10 py-2 px-2 rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
            class="icon icon-tabler icons-tabler-filled icon-tabler-layout-dashboard">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
                d="M9 3a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2zm0 12a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2zm10 -4a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2zm0 -8a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-4a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2z" />
        </svg>
        <p x-show='!expanded'>
            Discover
        </p>
    </a>
    <div x-show="tooltip"
        class="z-50 text-sm absolute top-0 left-full bg-white text-black border rounded-md py-1 px-2 ml-1 mt-1 text-nowrap"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
        Discover
    </div>
</li>

{{-- Classes --}}
<li x-data="{ tooltip: false }" class="relative">
    <a href="{{ route('classes') }}" wire:navigate x-on:mouseenter="tooltip = !tooltip"
        x-on:mouseleave="tooltip = false" :class="expanded ? 'w-fit' : 'w-full'"
        class="inline-flex items-center gap-3 text-sm font-medium hover:bg-[#F2F2F2]/10 py-2 px-2 rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
            class="icon icon-tabler icons-tabler-filled icon-tabler-book">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
                d="M12.088 4.82a10 10 0 0 1 9.412 .314a1 1 0 0 1 .493 .748l.007 .118v13a1 1 0 0 1 -1.5 .866a8 8 0 0 0 -8 0a1 1 0 0 1 -1 0a8 8 0 0 0 -7.733 -.148l-.327 .18l-.103 .044l-.049 .016l-.11 .026l-.061 .01l-.117 .006h-.042l-.11 -.012l-.077 -.014l-.108 -.032l-.126 -.056l-.095 -.056l-.089 -.067l-.06 -.056l-.073 -.082l-.064 -.089l-.022 -.036l-.032 -.06l-.044 -.103l-.016 -.049l-.026 -.11l-.01 -.061l-.004 -.049l-.002 -.068v-13a1 1 0 0 1 .5 -.866a10 10 0 0 1 9.412 -.314l.088 .044l.088 -.044z" />
        </svg>
        <p x-show='!expanded'>
            Classes
        </p>
    </a>
    <div x-show="tooltip"
        class="z-50 text-sm absolute top-0 left-full bg-white text-black border rounded-md py-1 px-2 ml-1 mt-1 text-nowrap"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
        Classes
    </div>
</li>

<li x-data="{ tooltip: false }" class="relative">
    <a href="{{ route('tutor.schedule') }}" wire:navigate x-on:mouseenter="tooltip = !tooltip"
        x-on:mouseleave="tooltip = false" :class="expanded ? 'w-fit' : 'w-full'"
        class="inline-flex items-center gap-3 text-sm font-medium hover:bg-[#F2F2F2]/10 py-2 px-2 rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
            class="icon icon-tabler icons-tabler-filled icon-tabler-calendar">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
                d="M16 2a1 1 0 0 1 .993 .883l.007 .117v1h1a3 3 0 0 1 2.995 2.824l.005 .176v12a3 3 0 0 1 -2.824 2.995l-.176 .005h-12a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-12a3 3 0 0 1 2.824 -2.995l.176 -.005h1v-1a1 1 0 0 1 1.993 -.117l.007 .117v1h6v-1a1 1 0 0 1 1 -1zm3 7h-14v9.625c0 .705 .386 1.286 .883 1.366l.117 .009h12c.513 0 .936 -.53 .993 -1.215l.007 -.16v-9.625z" />
            <path
                d="M12 12a1 1 0 0 1 .993 .883l.007 .117v3a1 1 0 0 1 -1.993 .117l-.007 -.117v-2a1 1 0 0 1 -.117 -1.993l.117 -.007h1z" />
        </svg>
        <p x-show='!expanded'>
            Schedule
        </p>
    </a>
    <div x-show="tooltip"
        class="z-50 text-sm absolute top-0 left-full bg-white text-black border rounded-md py-1 px-2 ml-1 mt-1 text-nowrap"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
        Schedule
    </div>
</li>
