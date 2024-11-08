{{-- <!-- resources/views/livewire/pages/bookmarks.blade.php -->
<div class="w-80 p-4 bg-white shadow-lg rounded-lg h-full relative">
    <h2 class="text-lg font-semibold mb-4">Bookmarks</h2>

    @if($bookmarkedTutors->isEmpty())

        <p class="text-center text-gray-500">You have no Bookmarks</p>
                            <!-- Loading indicator -->
                            <div x-show="$el.scrollHeight - $el.scrollTop <= $el.clientHeight" x-intersect="$wire.loadMore()">
                                <div wire:loading wire:target="loadMore" class="w-full flex flex-col bg-white rounded-xl">
                                    <div class="flex justify-center">
                                        <div class="animate-spin inline-block size-7 border-[3px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500" role="status" aria-label="loading">
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

                @foreach($bookmarkedTutors as $bookmark)
                <li class="mb-2 border-b border-gray-200 pb-2 cursor-pointer hover:bg-gray-100 transition-colors duration-200 ease-in-out"
                    wire:click="removeBookmark({{ $bookmark->id }})">
                    <a href="{{ route('tutors', ['tutor_id' => $bookmark->tutor_id]) }}">
                        <div class="flex items-center">
                            @if ($bookmark->tutor->avatar !== null)
                                <img class="rounded-md mr-3 h-6 w-6" src="{{ Storage::url($bookmark->tutor->avatar) }}">
                            @else
                                <img class="rounded-md mr-3 h-6 w-6" src="{{ asset('images/default.jpg') }}">
                            @endif
                            <div class="flex-1">
                                <p class="text-gray-700">
                                    {{ $bookmark->tutor->user->fname }} {{ $bookmark->tutor->user->lname }}
                                </p>
                            </div>
                        </div>
                    </a>
                </li>
                @endforeach

                    <!-- Loading indicator -->
                    <div x-show="$el.scrollHeight - $el.scrollTop <= $el.clientHeight" x-intersect="$wire.loadMore()">
                        <div wire:loading wire:target="loadMore" class="w-full flex flex-col bg-white rounded-xl">
                            <div class="flex justify-center">
                                <div class="animate-spin inline-block size-7 border-[3px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500" role="status" aria-label="loading">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
            </ul>
        </div>
    @endif

</div>
 --}}


 <!-- resources/views/livewire/pages/bookmarks.blade.php -->
<div class="w-80 p-4 bg-white shadow-lg rounded-lg h-full relative">
    <h2 class="text-lg font-semibold mb-4">Bookmarks</h2>

    @if($bookmarkedTutors->isEmpty())

        <p class="text-center text-gray-500">You have no Bookmarks</p>

        <!-- Loading indicator -->
        <div x-show="$el.scrollHeight - $el.scrollTop <= $el.clientHeight" x-intersect="$wire.loadMore()">
            <div wire:loading wire:target="loadMore" class="w-full flex flex-col bg-white rounded-xl">
                <div class="flex justify-center">
                    <div class="animate-spin inline-block size-7 border-[3px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500" role="status" aria-label="loading">
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

                @foreach($bookmarkedTutors as $bookmark)
                <li class="mb-2 border-b border-gray-200 pb-2 cursor-pointer hover:bg-gray-100 transition-colors duration-200 ease-in-out"
                    wire:click="redirectToSearch('{{ $bookmark->tutor->user->fname }}')">
                    <div class="flex items-center">
                        @if ($bookmark->tutor->avatar !== null)
                            <img class="rounded-md mr-3 h-6 w-6" src="{{ Storage::url($bookmark->tutor->avatar) }}">
                        @else
                            <img class="rounded-md mr-3 h-6 w-6" src="{{ asset('images/default.jpg') }}">
                        @endif
                        <div class="flex-1">
                            <p class="text-gray-700">
                                {{ $bookmark->tutor->user->fname }} {{ $bookmark->tutor->user->lname }}
                            </p>
                        </div>
                    </div>
                </li>
                @endforeach

                <!-- Loading indicator -->
                <div x-show="$el.scrollHeight - $el.scrollTop <= $el.clientHeight" x-intersect="$wire.loadMore()">
                    <div wire:loading wire:target="loadMore" class="w-full flex flex-col bg-white rounded-xl">
                        <div class="flex justify-center">
                            <div class="animate-spin inline-block size-7 border-[3px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500" role="status" aria-label="loading">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- might be needed to trigger load more --}}
                <div class="m-2">
                    {{-- End --}}
                </div>
            </ul>
        </div>
    @endif

</div>
