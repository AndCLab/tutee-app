<div class="w-80 p-4 bg-white shadow-lg rounded-lg h-full relative">
    <h2 class="text-lg font-semibold mb-4">Messages</h2>

    @if(empty($conversations))
        <p class="text-center text-gray-500">No messages found</p>
                                        <!-- Infinite Scroll Trigger for Loading More Messages -->
                                        <div x-intersect="$wire.loadMore()">
                                            <!-- Loading indicator -->
                                            <div wire:loading wire:target="loadMore" class="w-full flex flex-col bg-white rounded-xl mt-4">
                                                <div class="flex justify-center">
                                                    <div class="animate-spin inline-block size-7 border-[3px] border-current border-t-transparent text-blue-600 rounded-full" role="status" aria-label="loading">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    @else
        <div class="mb-4 max-h-124 overflow-y-auto soft-scrollbar"
             x-data="{ page: 1, isLoading: false }"
             @scroll="if ($el.scrollTop + $el.clientHeight >= $el.scrollHeight - 10 && !isLoading) {
                isLoading = true;
                page++;
                $wire.loadMore().then(() => isLoading = false);
             }">
            <ul wire:ignore.self class="max-h-[400px] overflow-y-auto">
                @foreach($conversations as $conversation)
                    <li class="mb-4">
                        <div class="flex items-center space-x-3">
                            @php
                            // Determine which user to display (user1 or user2)
                            $otherUser = $conversation->user1->id === Auth::id() ? $conversation->user2 : $conversation->user1;
                            @endphp

                            <!-- Check if the other user has a profile photo -->
                            @if ($otherUser->profile_photo_url)
                                <img class="w-10 h-10 rounded-full flex-shrink-0" src="{{ $otherUser->profile_photo_url }}" alt="{{ $otherUser->name }}">
                            @else
                                <img class="w-10 h-10 rounded-full flex-shrink-0" src="{{ asset('images/default.jpg') }}" alt="Default image">
                            @endif

                            <div class="w-full overflow-hidden ">
                                <p class="font-semibold text-gray-800">{{ $otherUser['name'] }}</p>
                                <p class="text-gray-600 truncate overflow-hidden text-ellipsis whitespace-nowrap">{{ $conversation['lastMessage']['message'] }}</p>
                                <p class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($conversation['lastMessage']['created_at'])->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
                                <!-- Infinite Scroll Trigger for Loading More Messages -->
            <div x-intersect="$wire.loadMore()">
                <!-- Loading indicator -->
                <div wire:loading wire:target="loadMore" class="w-full flex flex-col bg-white rounded-xl mt-4">
                    <div class="flex justify-center">
                        <div class="animate-spin inline-block size-7 border-[3px] border-current border-t-transparent text-blue-600 rounded-full" role="status" aria-label="loading">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            </ul>

        </div>
    @endif


</div>
