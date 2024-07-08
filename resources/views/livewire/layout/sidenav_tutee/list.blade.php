{{-- Discover --}}
<li x-data="{ tooltip: false }" class="relative">
    <a href="{{ route('tutee.discover') }}"
        x-on:mouseenter="tooltip = !tooltip" x-on:mouseleave="tooltip = false"
        :class="expanded ? 'w-fit' : 'w-full' "
        class="inline-flex items-center gap-3 text-sm font-medium hover:bg-[#F2F2F2] py-2 px-2 rounded-md">
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
    <div x-show="tooltip" class="z-50 text-sm absolute top-0 left-full bg-white border-graphite border-2 rounded-md py-1 px-2 ml-1 mt-1 text-nowrap"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        >
        Discover
    </div>
</li>

{{-- Tutor --}}
<li x-data="{ tooltip: false }" class="relative">
    <a href="{{ route('tutors') }}"
        x-on:mouseenter="tooltip = !tooltip" x-on:mouseleave="tooltip = false"
        :class="expanded ? 'w-fit' : 'w-full' "
        class="inline-flex items-center gap-3 text-sm font-medium hover:bg-[#F2F2F2] py-2 px-2 rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
            class="icon icon-tabler icons-tabler-filled icon-tabler-ballpen">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
                d="M17.828 2a3 3 0 0 1 1.977 .743l.145 .136l1.171 1.17a3 3 0 0 1 .136 4.1l-.136 .144l-1.706 1.707l2.292 2.293a1 1 0 0 1 .083 1.32l-.083 .094l-4 4a1 1 0 0 1 -1.497 -1.32l.083 -.094l3.292 -3.293l-1.586 -1.585l-7.464 7.464a3.828 3.828 0 0 1 -2.474 1.114l-.233 .008c-.674 0 -1.33 -.178 -1.905 -.508l-1.216 1.214a1 1 0 0 1 -1.497 -1.32l.083 -.094l1.214 -1.216a3.828 3.828 0 0 1 .454 -4.442l.16 -.17l10.586 -10.586a3 3 0 0 1 1.923 -.873l.198 -.006zm0 2a1 1 0 0 0 -.608 .206l-.099 .087l-1.707 1.707l2.586 2.585l1.707 -1.706a1 1 0 0 0 .284 -.576l.01 -.131a1 1 0 0 0 -.207 -.609l-.087 -.099l-1.171 -1.171a1 1 0 0 0 -.708 -.293z" />
        </svg>
        <p x-show='!expanded'>
            Tutors
        </p>
        <div x-show="tooltip" class="z-50 text-sm absolute top-0 left-full bg-white border-graphite border-2 rounded-md py-1 px-2 ml-1 mt-1 text-nowrap"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            >
            Tutors
        </div>
    </a>
</li>

{{-- Schedule --}}
<li x-data="{ tooltip: false }" class="relative">
    <a href="{{ route('tutee.schedule') }}"
        x-on:mouseenter="tooltip = !tooltip" x-on:mouseleave="tooltip = false"
        :class="expanded ? 'w-fit' : 'w-full' "
        class="inline-flex items-center gap-3 text-sm font-medium hover:bg-[#F2F2F2] py-2 px-2 rounded-md">
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
        <div x-show="tooltip" class="z-50 text-sm absolute top-0 left-full bg-white border-graphite border-2 rounded-md py-1 px-2 ml-1 mt-1 text-nowrap"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            >
            Schedule
    </div>
    </a>
</li>
