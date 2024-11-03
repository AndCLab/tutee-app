@props(['title'])

<div class="bg-info-50 w-full flex flex-col p-4 rounded-md">
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <svg
                class="text-info-800 w-5 h-5 mr-3 shrink-0"
                stroke="currentColor" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M11.25 11.25L11.2915 11.2293C11.8646 10.9427 12.5099 11.4603 12.3545 12.082L11.6455 14.918C11.4901 15.5397 12.1354 16.0573 12.7085 15.7707L12.75 15.75M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM12 8.25H12.0075V8.2575H12V8.25Z"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                </path>
            </svg>
            <div class="font-semibold text-info-800 text-sm whitespace-normal">
                {{ $title }}
            </div>
        </div>

    </div>
    <div class="text-info-800 pl-1 ml-7 grow text-sm">
        {{ $slot }}
    </div>
</div>
