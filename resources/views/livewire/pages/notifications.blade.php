<div class="w-96 p-4 bg-white shadow-lg rounded-lg h-full relative">
    @if(empty($notifications))
        <h2 class="text-lg font-semibold mb-4">Notifications</h2>
        <p class="text-center text-gray-500">You have no Notifications</p>
    @else
        <div class="mb-4 max-h-124 overflow-y-auto soft-scrollbar"
             x-data="{ page: 1, isLoading: false }"
             @scroll="if ($el.scrollTop + $el.clientHeight >= $el.scrollHeight - 10 && !isLoading) {
                isLoading = true;
                page++;
                $wire.loadMore().then(() => isLoading = false);
             }">
            <ul class="max-h-[400px] overflow-y-auto">
                <h2 class="text-lg font-semibold mb-4">Notifications</h2>
                @foreach($notifications as $date => $dateGroup)
                    <div class="pb-2">
                        <h3 class="text-gray-500 text-sm mb-1">{{ $date }}</h3>
                        @foreach($dateGroup as $notif)
                            <li class="mb-2 border-b border-gray-200 pb-2 cursor-pointer hover:bg-gray-100 transition-colors duration-200 ease-in-out"
                                wire:click="markAsRead({{ $notif['id'] }})" wire:key="notif-{{ $notif['id'] }}">
                                <div class="flex items-center">
                                    <!-- Notification Icon (SVG) -->
                                    @if($notif['type'] === 'venue')
                                        <svg class="mr-3 h-6 w-6" style="color:#0C3B2E" width="256px" height="256px" viewBox="0 0 24 24" fill="#0C3B2E" xmlns="http://www.w3.org/2000/svg">
                                            <!-- Venue SVG -->
                                            <g fill="#0C3B2E">
                                                <path fill="currentColor" d="M17.657 5.304c-3.124-3.073-8.189-3.073-11.313 0a7.78 7.78 0 0 0 0 11.13L12 21.999l5.657-5.565a7.78 7.78 0 0 0 0-11.13zM12 13.499c-.668 0-1.295-.26-1.768-.732a2.503 2.503 0 0 1 0-3.536c.472-.472 1.1-.732 1.768-.732s1.296.26 1.768.732a2.503 2.503 0 0 1 0 3.536c-.472.472-1.1.732-1.768.732z"/>
                                            </g>
                                        </svg>
                                    @elseif($notif['type'] === 'schedule')
                                        <svg class="mr-3 h-6 w-6" style="color:#0C3B2E" width="256px" height="256px" viewBox="0 0 24 24" fill="#0C3B2E" xmlns="http://www.w3.org/2000/svg">
                                            <!-- Schedule SVG -->
                                            <g fill="#0C3B2E">
                                                <path fill="currentColor" d="M19 6.184V6a3 3 0 1 0-6 0h-2a3 3 0 1 0-6 0v.184A2.997 2.997 0 0 0 3 9v9c0 1.654 1.346 3 3 3h12c1.654 0 3-1.346 3-3V9a2.997 2.997 0 0 0-2-2.816zM15 6a1 1 0 1 1 2 0v2a1 1 0 1 1-2 0V6zM7 6a1 1 0 1 1 2 0v2a1 1 0 1 1-2 0V6zm12 12c0 .551-.448 1-1 1H6c-.552 0-1-.449-1-1v-6h14v6z"/>
                                            </g>
                                        </svg>
                                    @elseif($notif['type'] === 'assignment')
                                        <svg class="mr-3 h-6 w-6" style="color:#0C3B2E" width="256px" height="256px" viewBox="0 0 24 24" fill="#0C3B2E" xmlns="http://www.w3.org/2000/svg">
                                            <!-- Assignment SVG -->
                                            <g fill="#0C3B2E">
                                                <path fill="currentColor" d="M17 3H7C5.346 3 4 4.346 4 6v12c0 1.654 1.346 3 3 3h10c1.654 0 3-1.346 3-3V6c0-1.654-1.346-3-3-3zM9 5h6v1c0 .551-.449 1-1 1h-4c-.551 0-1-.449-1-1V5zm9 13c0 .551-.449 1-1 1H7c-.551 0-1-.449-1-1V6c0-.551.449-1 1-1h1v1c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2V5h1c.551 0 1 .449 1 1v12zm-2-1H8a.5.5 0 0 1 0-1h8a.5.5 0 0 1 0 1zm0-3H8a.5.5 0 0 1 0-1h8a.5.5 0 0 1 0 1zm0-3H8a.5.5 0 0 1 0-1h8a.5.5 0 0 1 0 1z"/>
                                            </g>
                                        </svg>
                                    @else
                                        <svg class="mr-3 w-6 h-6" style="color:#0C3B2E" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                            <!-- Default SVG for other types -->
                                        </svg>
                                    @endif
                                    <div class="flex-1">
                                        <p class="{{ $notif['read'] ? 'text-gray-700' : 'text-blue-600 font-semibold' }}">
                                            {{ $notif['content'] }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </div>
                @endforeach

                <!-- Loading indicator -->
                <div x-show="$el.scrollHeight - $el.scrollTop <= $el.clientHeight" x-intersect='$wire.loadMore()'>
                    <div wire:loading wire:target="loadMore" class="w-full flex flex-col bg-white rounded-xl">
                        <div class="flex flex-auto flex-col justify-center items-center">
                            <div class="flex justify-center">
                                <div class="animate-spin inline-block size-7 border-[3px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500" role="status" aria-label="loading">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </ul>
        </div>
    @endif
</div>


{{-- <div class="w-96 p-4 bg-white shadow-lg rounded-lg h-full relative">
    @if(empty($notifications))
        <h2 class="text-lg font-semibold mb-4">Notifications</h2>
        <p class="text-center text-gray-500">You have no Notifications</p>
    @else
        <div class="mb-4 max-h-124 overflow-y-auto soft-scrollbar"
             x-data="{ page: 1, isLoading: false }"
             @scroll="if ($el.scrollTop + $el.clientHeight >= $el.scrollHeight - 10 && !isLoading) {
                isLoading = true;
                page++;
                $wire.loadMore().then(() => isLoading = false);
             }">
            <ul class="max-h-[400px] overflow-y-auto">
                @foreach($notifications as $date => $dateGroup)
                    <li class="mb-2 border-b border-gray-200 pb-2">
                        <h3 class="text-lg font-semibold mb-2">{{ $date }}</h3>
                        @foreach($dateGroup as $notif)
                            <li class="mb-2 border-b border-gray-200 pb-2 cursor-pointer hover:bg-gray-100 transition-colors duration-200 ease-in-out"
                                wire:click="markAsRead({{ $notif['id'] }})">
                                <div class="flex items-center">
                                    <!-- Notification Icon (SVG) -->
                                    @if($notif['type'] === 'venue')
                                        <svg class="mr-3 h-6 w-6" style="color:#0C3B2E" width="256px" height="256px" viewBox="0 0 24 24" fill="#0C3B2E" xmlns="http://www.w3.org/2000/svg">
                                            <!-- Venue SVG -->
                                            <g fill="#0C3B2E">
                                                <path fill="currentColor" d="M17.657 5.304c-3.124-3.073-8.189-3.073-11.313 0a7.78 7.78 0 0 0 0 11.13L12 21.999l5.657-5.565a7.78 7.78 0 0 0 0-11.13zM12 13.499c-.668 0-1.295-.26-1.768-.732a2.503 2.503 0 0 1 0-3.536c.472-.472 1.1-.732 1.768-.732s1.296.26 1.768.732a2.503 2.503 0 0 1 0 3.536c-.472.472-1.1.732-1.768.732z"/>
                                            </g>
                                        </svg>
                                    @elseif($notif['type'] === 'schedule')
                                        <svg class="mr-3 h-6 w-6" style="color:#0C3B2E" width="256px" height="256px" viewBox="0 0 24 24" fill="#0C3B2E" xmlns="http://www.w3.org/2000/svg">
                                            <!-- Schedule SVG -->
                                            <g fill="#0C3B2E">
                                                <path fill="currentColor" d="M19 6.184V6a3 3 0 1 0-6 0h-2a3 3 0 1 0-6 0v.184A2.997 2.997 0 0 0 3 9v9c0 1.654 1.346 3 3 3h12c1.654 0 3-1.346 3-3V9a2.997 2.997 0 0 0-2-2.816zM15 6a1 1 0 1 1 2 0v2a1 1 0 1 1-2 0V6zM7 6a1 1 0 1 1 2 0v2a1 1 0 1 1-2 0V6zm12 12c0 .551-.448 1-1 1H6c-.552 0-1-.449-1-1v-6h14v6z"/>
                                            </g>
                                        </svg>
                                    @elseif($notif['type'] === 'assignment')
                                        <svg class="mr-3 h-6 w-6" style="color:#0C3B2E" width="256px" height="256px" viewBox="0 0 24 24" fill="#0C3B2E" xmlns="http://www.w3.org/2000/svg">
                                            <!-- Assignment SVG -->
                                            <g fill="#0C3B2E">
                                                <path fill="currentColor" d="M17 3H7C5.346 3 4 4.346 4 6v12c0 1.654 1.346 3 3 3h10c1.654 0 3-1.346 3-3V6c0-1.654-1.346-3-3-3zM9 5h6v1c0 .551-.449 1-1 1h-4c-.551 0-1-.449-1-1V5zm9 13c0 .551-.449 1-1 1H7c-.551 0-1-.449-1-1V6c0-.551.449-1 1-1h1v1c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2V5h1c.551 0 1 .449 1 1v12zm-2-1H8a.5.5 0 0 1 0-1h8a.5.5 0 0 1 0 1zm0-3H8a.5.5 0 0 1 0-1h8a.5.5 0 0 1 0 1zm0-3H8a.5.5 0 0 1 0-1h8a.5.5 0 0 1 0 1z"/>
                                            </g>
                                        </svg>
                                    @else
                                        <svg class="mr-3 w-6 h-6" style="color:#0C3B2E" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                            <!-- Default SVG for other types -->
                                        </svg>
                                    @endif
                                    <div class="flex-1">
                                        <p class="{{ $notif['read'] ? 'text-gray-700' : 'text-blue-600 font-semibold' }}">
                                            {{ $notif['content'] }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </li>
                @endforeach

                    <!-- Loading indicator -->
                    <div x-show="$el.scrollHeight - $el.scrollTop <= $el.clientHeight" x-intersect='$wire.loadMore()'>
                        <div wire:loading wire:target="loadMore" class="w-full flex flex-col bg-white rounded-xl">
                            <div class="flex flex-auto flex-col justify-center items-center">
                                <div class="flex justify-center">
                                    <div class="animate-spin inline-block size-7 border-[3px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500" role="status" aria-label="loading">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>         
            </ul>
        </div>
    @endif
</div> --}}