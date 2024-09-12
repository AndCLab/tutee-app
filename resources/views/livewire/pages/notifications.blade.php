<div class="w-96 p-4 bg-white shadow-lg rounded-lg h-full relative">
    
    {{-- <div>
        <p>{{ $showAll ? 'Showing all notifications' : 'Showing limited notifications' }}</p>
    </div> --}}

    <!-- Check if there are notifications -->
    @if($notifications->isEmpty())
        <!-- Notifications Header -->
        <h2 class="text-lg font-semibold mb-4">Notifications</h2>
        <!-- Display message if no notifications -->
        <p class="text-center text-gray-500">You have no Notifications</p>
    @else
        <!-- Scrollable list of notifications -->
        <div class="mb-4">
            <ul id="notificationsList" class="{{ $showAll ? 'max-h-96 overflow-y-auto' : 'max-h-60 overflow-hidden' }}">
            {{-- <ul id="notificationsList" class="{{ $showAll ? 'max-h-[65vh] overflow-y-auto' : 'max-h-[50vh] overflow-hidden' }}"> --}}
                <!-- Notifications Header -->
                <h2 class="text-lg font-semibold mb-4">Notifications</h2>   
                @foreach($notifications as $notif)
                    <li class="mb-2 border-b border-gray-200 pb-2 min-h-[100px]"> <!-- Adjust min-height as needed -->
                        <!-- Check if the notification date is today -->
                        @php
                            $isToday = \Carbon\Carbon::parse($notif->date)->isToday();
                        @endphp
                        <p class="text-gray-500 text-sm">
                            <strong>{{ $isToday ? 'Today' : \Carbon\Carbon::parse($notif->date)->format('F d, Y') }}</strong>
                        </p>
                        <p class="text-gray-700">{{ $notif->content }}</p>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Button always visible outside the scrollable area -->
        <div class="flex justify-center items-center bg-white dark:bg-secondary-800">
            <button wire:click="toggleShowAll" onclick="scrollToTop()" class="text-blue-500 text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 2a6 6 0 00-6 6v2.586L2.293 13.293a1 1 0 001.414 1.414l1.293-1.293V14a6 6 0 1012 0v-2a6 6 0 00-6-6zm3.292 8.708l-3.292 3.292V8a1 1 0 112 0v3.293l1.292-1.292a1 1 0 011.415 1.415z" />
                </svg>
                {{ $showAll ? 'Show less' : 'View all notifications' }}
            </button>
        </div>
    @endif
</div>

<script>
    function scrollToTop() {
        const notificationsList = document.getElementById('notificationsList');
        if (notificationsList) {
            notificationsList.scrollTop = 0;
        }
    }
</script>
