{{-- resources\views\livewire\pages\tutee\tutor_components\selected_tutor.blade.php --}}

@php
    use App\Models\Fields;
    use App\Models\Bookmark;
    use App\Livewire\Notifications;
    use App\Models\Notification;
    // use App\Models\TuteeNotification;
    // use App\Models\TutorNotification;
    use Carbon\Carbon;
    use App\Models\Classes;
    use App\Models\ClassRoster;

@endphp

{{-- h-[77vh] --}}
<div wire:loading wire:target="selectTutor" class="w-full flex flex-col bg-white rounded-xl">
    <div class="flex flex-auto flex-col justify-center items-center p-4 md:p-5">
        <div class="flex justify-center">
            <div class="animate-spin inline-block size-8 border-[3px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500" role="status" aria-label="loading">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</div>
<div wire:loading.remove wire:target="selectTutor">
    {{-- profile and bio --}}
    <div>
        <div class="flex flex-wrap gap-4">
            @if ($selectedTutor->user->avatar !== null)
                <img class="rounded-md size-24" src="{{ Storage::url($selectedTutor->user->avatar) }}">
            @else
                <img class="rounded-md size-24" src="{{ asset('images/default.jpg') }}">
            @endif
            <div class="flex flex-wrap flex-row justify-between w-full gap-2">
                <div class="space-y-2">
                    <div class="flex gap-2 items-center">
                        <h2 class="text-xl font-semibold truncate">{{ $tutor_name }}</h2>

                    <div x-data="{
                            isHovered: false,
                            isBookmarked: @entangle('isBookmarked')
                        }"
                        x-init="
                            Livewire.on('bookmarkUpdated', value => {
                                isBookmarked = value;
                            });
                        ">
                        <!-- Button for toggling bookmark -->
                        <x-loading-button wire:click="toggleBookmark" wireTarget="toggleBookmark" @mouseover="isHovered = true" @mouseleave="isHovered = false" >
                            <!-- SVG for when not bookmarked -->
                            <template x-if="!isBookmarked">
                                <svg :class="isHovered ? 'text-green-500' : 'text-gray-500'" class="bookMark size-5 cursor-pointer" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 10.5H14M12 8.5V12.5M8.25 5H15.75C16.4404 5 17 5.58763 17 6.3125V19L12 15.5L7 19V6.3125C7 5.58763 7.55964 5 8.25 5Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </template>

                            <!-- SVG for when bookmarked but not hovered -->
                            <template x-if="isBookmarked && !isHovered">
                                <svg class="text-gray-500 bookMark size-5 cursor-pointer" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.5 10.5L11.5 11.5L14 9M8.25 5H15.75C16.4404 5 17 5.58763 17 6.3125V19L12 15.5L7 19V6.3125C7 5.58763 7.55964 5 8.25 5Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </template>

                            <!-- SVG for when bookmarked and hovered -->
                            <template x-if="isBookmarked && isHovered">
                                <svg class="text-red-500 bookMark size-5 cursor-pointer" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.5 10.5L11.5 11.5L14 9M8.25 5H15.75C16.4404 5 17 5.58763 17 6.3125V19L12 15.5L7 19V6.3125C7 5.58763 7.55964 5 8.25 5Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </template>

                            {{-- Bookmark --}}

                        </x-loading-button>
                    </div>


                        {{-- <p>Is Bookmarked: {{ $isBookmarked ? 'true' : 'false' }}</p> --}}
                    </div>
                    <div class="flex flex-col gap-2">
                        <x-wui-badge class="w-fit" icon='users' flat warning
                            label="{{ $ST_GRClasses }} Group Classes" />
                        <x-wui-badge class="w-fit" icon='user' flat purple
                            label="{{ $ST_INDIClasses }} Individual Classes" />
                    </div>
                </div>
                <div>
                    <x-wui-button icon='chat-alt-2' sm label='Message {{ $selectedTutor->user->fname }}' />
                </div>
            </div>
        </div>
        <p class="mt-2">
            {{ $selectedTutor->bio }}
        </p>
    </div>

    {{-- fields card --}}
    <div class="space-y-2 mt-4">
        <h2 class="text-lg font-semibold">{{ $selectedTutor->user->fname }}'s Fields</h2>

        @foreach ($fields = Fields::where('user_id', $selectedTutor->user->id)->get() as $index => $item)
            @if (in_array(strtolower($item->field_name), array_map('strtolower', $class_fields)))
                <x-wui-badge flat rose label="{{ $item->field_name }}" />
            @else
                <x-wui-badge flat slate label="{{ $item->field_name }}" />
            @endif
        @endforeach
    </div>

    {{-- schedule card --}}
    <div class="space-y-2 mt-4">
        <h2 class="text-lg font-semibold">{{ $selectedTutor->user->fname }}'s Schedule</h2>
        @forelse ($selectedClass as $count => $class)
            @break($count === 3)
            <div class="flex justify-between items-start gap-4 p-4 rounded border">

                {{-- parent div --}}
                    <x-wui-icon name='calendar' class="size-6 text-[#0C3B2E]" solid />
                    <div class="space-y-1 w-full">
                        {{-- child 1 --}}
                        <div class="lg:inline-flex items-center gap-2">
                            <p class="text-[#8F8F8F] font-medium">
                                {{ $class->class_name }}
                            </p>
                            @if ($class->class_category == 'group')
                                <x-wui-badge flat warning
                                    label="{{ $class->class_category }}" />
                            @else
                                <x-wui-badge flat purple
                                    label="{{ $class->class_category }}" />
                            @endif
                        </div>

                        {{-- child 2 --}}
                        <div class="line-clamp-2">
                            {{ $class->class_description }}
                        </div>

                        {{-- child 3 --}}
                        <div class="lg:flex flex-wrap lg:flex-nowrap lg:justify-between lg:items-center">
                            <div class="text-[#64748B] inline-flex gap-2 items-center">
                                <x-wui-icon name='calendar' class="size-5" />
                                <p class="font-light text-sm line-clamp-1">
                                    {{ Carbon::create($class->schedule->start_date)->format('l jS \\of F Y h:i A') }}
                                </p>
                            </div>
                            <div>
                                <!-- ... -->
                                <div>
                                    <x-secondary-button class="w-full lg:w-fit text-nowrap"
                                    wire:click="classView({{ $class->id }})" wireTarget="classView({{ $class->id }})">
                                    Join Class
                                    </x-secondary-button>

                                    <!-- Class View Modal -->
                                    @include('livewire.pages.tutee.post_components.modals.class_view_modal')

                                </div>

                            </div>
                        </div>
                    </div>
            </div>
        @empty
            <div class="flex justify-between items-end p-4 rounded border">
                No Schedule Yet
            </div>
        @endforelse
    </div>

    {{-- reviews --}}
    <div class="space-y-2 mt-4 text-[#0F172A]">
        <h2 class="text-lg font-semibold">{{ $selectedTutor->user->fname }}'s Reviews</h2>

        @forelse ($selectedReview as $review)
            {{-- parent div --}}
            <div class="flex items-start gap-3">
                <img class="rounded-full size-10" src="{{ asset('images/default.jpg') }}">
                <div class="flex flex-col w-full space-y-2">
                    {{-- reviewer's name --}}
                    <div class="inline-flex gap-2 items-center text-sm">
                        <p class="font-semibold">
                            {{ $review->tutee->user->fname . ' ' . $review->tutee->user->lname }}
                        </p>

                        {{-- star --}}
                        <div x-data="{
                            disabled: false,
                            max_stars: 5,
                            stars: @js($review->rating),
                            value: @js($review->rating),
                        }" x-init="this.stars = this.value">
                            <div class="flex flex-col items-center max-w-6xl mx-auto jusitfy-center">
                                <div x-ref="rated" class="absolute -mt-2 text-xs font-medium text-gray-900 duration-300 ease-out -translate-y-full opacity-0">Rated <span x-text="value"></span> Stars</div>
                                <ul class="flex">
                                    <template x-for="star in max_stars">
                                        <li :class="{ 'text-gray-400 cursor-not-allowed': disabled}">
                                            <svg x-show="star > stars" class="w-5 h-5 text-gray-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M128,189.09l54.72,33.65a8.4,8.4,0,0,0,12.52-9.17l-14.88-62.79,48.7-42A8.46,8.46,0,0,0,224.27,94L160.36,88.8,135.74,29.2a8.36,8.36,0,0,0-15.48,0L95.64,88.8,31.73,94a8.46,8.46,0,0,0-4.79,14.83l48.7,42L60.76,213.57a8.4,8.4,0,0,0,12.52,9.17Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                                            <svg x-show="star <= stars" class="w-5 h-5 text-gray-900 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M234.29,114.85l-45,38.83L203,211.75a16.4,16.4,0,0,1-24.5,17.82L128,198.49,77.47,229.57A16.4,16.4,0,0,1,53,211.75l13.76-58.07-45-38.83A16.46,16.46,0,0,1,31.08,86l59-4.76,22.76-55.08a16.36,16.36,0,0,1,30.27,0l22.75,55.08,59,4.76a16.46,16.46,0,0,1,9.37,28.86Z"/></svg>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <span class="text-[#64748B]">rated {{ $review->rating }}/5</span>
                    </div>

                    {{-- reviewer's description --}}
                    <div x-data="{ isCollapsed: true }">
                        <div :class="isCollapsed ? 'line-clamp-2 max-h-12' : 'max-h-auto'" class="overflow-hidden">
                            {{ $review->remarks }}
                        </div>

                        <button @click="isCollapsed = !isCollapsed" class="mt-2 text-xs underline">
                            <span x-text="isCollapsed ? 'Read More' : 'Show Less'"></span>
                        </button>
                    </div>

                    {{-- review date --}}
                    <div class="text-[#64748B] inline-flex gap-2 items-center">
                        <x-wui-icon name='calendar' class="size-5" />
                        <p class="font-light text-sm">
                            Posted on {{ Carbon::parse($review->created_at)->format('l, F d Y g:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex justify-between items-end p-4 rounded border">
                No Reviews Yet
            </div>
        @endforelse
    </div>


</div>

