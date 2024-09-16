{{-- 
<div class="w-96 p-4 bg-white shadow-lg rounded-lg h-full relative">
    
    <!-- Check if there are notifications -->
    @if(empty($notifications))
        <!-- Notifications Header -->
        <h2 class="text-lg font-semibold mb-4">Notifications</h2>
        <!-- Display message if no notifications -->
        <p class="text-center text-gray-500">You have no Notifications</p>
    @else
        <!-- Scrollable list of notifications -->
        <div class="mb-4 max-h-124 overflow-y-auto soft-scrollbar" x-data="{ page: 1 }" @scroll="if ($el.scrollTop + $el.clientHeight >= $el.scrollHeight - 10) { page++; $wire.loadMore(); }">
            <ul id="notificationsList">
                <!-- Notifications Header -->
                <h2 class="text-lg font-semibold mb-4">Notifications</h2>   
                @foreach($notifications as $notif)
                    <li class="mb-2 border-b border-gray-200 pb-2 min-h-[100px]">
                        @php
                                $notificationDate = isset($notif['date']) ? \Carbon\Carbon::parse($notif['date']) : null;
                                $isToday = $notificationDate ? $notificationDate->isToday() : false;
                        @endphp
                        <p class="text-gray-500 text-sm">
                            <strong>{{ $isToday ? 'Today' : \Carbon\Carbon::parse($notif['date'])->format('F d, Y') }}</strong>
                        </p>

                        <p class="text-gray-700">{{ $notif['content'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Loading indicator -->
        <div x-intersect.full.threshold.50='$wire.loadMore()'>
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
    @endif
</div>



 --}}


 <div class="w-96 p-4 bg-white shadow-lg rounded-lg h-full relative">
    <!-- Check if there are notifications -->
    @if(empty($notifications))
        <!-- Notifications Header -->
        <h2 class="text-lg font-semibold mb-4">Notifications</h2>
        <!-- Display message if no notifications -->
        <p class="text-center text-gray-500">You have no Notifications</p>
    @else
        <!-- Scrollable list of notifications -->
        <div class="mb-4 max-h-124 overflow-y-auto soft-scrollbar" x-data="{ page: 1 }" @scroll="if ($el.scrollTop + $el.clientHeight >= $el.scrollHeight - 10) { page++; $wire.loadMore(); }">
            <ul id="notificationsList">
                <!-- Notifications Header -->
                <h2 class="text-lg font-semibold mb-4">Notifications</h2>
                @foreach($notifications as $notif)
                    @php
                        // Check if the notification is read
                        $isRead = isset($notif['read']) && $notif['read'];
                        $notificationDate = isset($notif['date']) ? \Carbon\Carbon::parse($notif['date']) : null;
                        $isToday = $notificationDate ? $notificationDate->isToday() : false;
                    @endphp
                    
                    <!-- Apply different text color for unread notifications, and hover effect for background color -->
                    <li class="mb-2 border-b border-gray-200 pb-2 min-h-[100px] cursor-pointer hover:bg-gray-100 transition-colors duration-200 ease-in-out"
                        wire:click="markAsRead({{ $notif['id'] }})">
                        
                        <!-- Notification Date -->
                        <p class="text-gray-500 text-sm">
                            <strong>{{ $isToday ? 'Today' : \Carbon\Carbon::parse($notif['date'])->format('F d, Y') }}</strong>
                        </p>

                        <!-- Notification Content: Highlight unread notifications with a different text color -->
                        <p class="{{ $isRead ? 'text-gray-700' : 'text-blue-600 font-semibold' }}">
                            {{ $notif['content'] }}
                        </p>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Loading indicator -->
        <div x-intersect.full.threshold.50='$wire.loadMore()'>
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
    @endif
</div>



