<div>
    <div class="flex flex-col gap-8">
        {{-- STEP 1 --}}
        <div class="flex flex-col gap-2">
            <p class="font-semibold text-xl @if ($count >= 1) text-[#1589C3] @endif">Step 1</p>
            <div class="flex h-11 items-center gap-2">
                <div class="h-full w-1 rounded-md @if ($count === 1) bg-[#1589C3] @endif"></div>
                <svg class="@if ($count >= 1) text-[#1589C3] @endif" xmlns="http://www.w3.org/2000/svg"
                    width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-square-rounded">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 13a3 3 0 1 0 0 -6a3 3 0 0 0 0 6z" />
                    <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                    <path d="M6 20.05v-.05a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v.05" />
                </svg>
                <div>
                    <p class="text-xl font-semibold @if ($count >= 1) text-[#1589C3] @endif">Role</p>
                    <p class="text-[#64748B]">Specify your role</p>
                </div>
            </div>
        </div>

        {{-- STEP 2 --}}
        <div class="flex flex-col gap-2">
            <p class="font-semibold text-xl @if ($count >= 2) text-[#1589C3] @endif">Step 2</p>
            <div class="flex h-11 items-center gap-2">
                <div class="h-full w-1 rounded-md @if ($count === 2) bg-[#1589C3] @endif"></div>
                <svg class="@if ($count >= 2) text-[#1589C3] @endif" xmlns="http://www.w3.org/2000/svg"
                    width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-list-details">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M13 5h8" />
                    <path d="M13 9h5" />
                    <path d="M13 15h8" />
                    <path d="M13 19h5" />
                    <path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                    <path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                </svg>
                <div>
                    <p class="text-xl font-semibold @if ($count >= 2) text-[#1589C3] @endif">Form</p>
                    <p class="text-[#64748B]">Information about you</p>
                </div>
            </div>
        </div>

        {{-- STEP 3 --}}
        <div class="flex flex-col gap-2">
            <p class="font-semibold text-xl @if ($count >= 3) text-[#1589C3] @endif">Step 3</p>
            <div class="flex h-11 items-center gap-2">
                <div class="h-full w-1 rounded-md @if ($count === 3) bg-[#1589C3] @endif"></div>
                <svg class="@if ($count >= 3) text-[#1589C3] @endif"
                    xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                    fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-heart">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M6.979 3.074a6 6 0 0 1 4.988 1.425l.037 .033l.034 -.03a6 6 0 0 1 4.733 -1.44l.246 .036a6 6 0 0 1 3.364 10.008l-.18 .185l-.048 .041l-7.45 7.379a1 1 0 0 1 -1.313 .082l-.094 -.082l-7.493 -7.422a6 6 0 0 1 3.176 -10.215z" />
                </svg>

                <div>
                    <p class="text-xl font-semibold @if ($count >= 3) text-[#1589C3] @endif">Fields
                    </p>
                    <p class="text-[#64748B]">What are your interests?</p>
                </div>
            </div>
        </div>

        {{-- STEP 4 --}}
        <div class="flex flex-col gap-2">
            <p class="font-semibold text-xl @if ($count >= 4) text-[#1589C3] @endif">Step 3</p>
            <div class="flex h-11 items-center gap-2">
                <div class="h-full w-1 rounded-md @if ($count === 4) bg-[#1589C3] @endif"></div>
                <svg class="@if ($count >= 4) text-[#1589C3] @endif"
                    xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                    fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-bounce-right">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M14.143 11.486a1 1 0 0 1 1.714 1.028c-1.502 2.505 -2.41 4.89 -2.87 7.65c-.16 .956 -1.448 1.15 -1.881 .283c-2.06 -4.12 -3.858 -4.976 -6.79 -3.998a1 1 0 1 1 -.632 -1.898c3.2 -1.067 5.656 -.373 7.803 2.623l.091 .13l.011 -.04c.522 -1.828 1.267 -3.55 2.273 -5.3l.28 -.478z" />
                    <path d="M18 4a3 3 0 1 0 0 6a3 3 0 0 0 0 -6z" />
                </svg>
                <div>
                    <p class="text-xl font-semibold @if ($count >= 4) text-[#1589C3] @endif">Confirmation
                    </p>
                    <p class="text-[#64748B]">Get your Tutee account</p>
                </div>
            </div>
        </div>

    </div>

</div>
