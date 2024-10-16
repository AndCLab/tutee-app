<div class="text-sm pt-2 rounded-md">
    <div class="flex flex-wrap gap-3 items-start">
        @if ($tutor->user->avatar !== null)
            <img class="border-2 border-[#F1F5F9] overflow-hidden rounded-md size-16"
                src="{{ Storage::url($tutor->user->avatar) }}">
        @else
            <img class="border-2 border-[#F1F5F9] overflow-hidden rounded-md size-16"
                src="{{ asset('images/default.jpg') }}">
        @endif
        <div class="flex flex-col space-y-1">
            <div class="inline-flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 text-[#FFBA00]">
                    <path fill-rule="evenodd" d="M10 1c-1.828 0-3.623.149-5.371.435a.75.75 0 0 0-.629.74v.387c-.827.157-1.642.345-2.445.564a.75.75 0 0 0-.552.698 5 5 0 0 0 4.503 5.152 6 6 0 0 0 2.946 1.822A6.451 6.451 0 0 1 7.768 13H7.5A1.5 1.5 0 0 0 6 14.5V17h-.75C4.56 17 4 17.56 4 18.25c0 .414.336.75.75.75h10.5a.75.75 0 0 0 .75-.75c0-.69-.56-1.25-1.25-1.25H14v-2.5a1.5 1.5 0 0 0-1.5-1.5h-.268a6.453 6.453 0 0 1-.684-2.202 6 6 0 0 0 2.946-1.822 5 5 0 0 0 4.503-5.152.75.75 0 0 0-.552-.698A31.804 31.804 0 0 0 16 2.562v-.387a.75.75 0 0 0-.629-.74A33.227 33.227 0 0 0 10 1ZM2.525 4.422C3.012 4.3 3.504 4.19 4 4.09V5c0 .74.134 1.448.38 2.103a3.503 3.503 0 0 1-1.855-2.68Zm14.95 0a3.503 3.503 0 0 1-1.854 2.68C15.866 6.449 16 5.74 16 5v-.91c.496.099.988.21 1.475.332Z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-medium">{{ $tutor->user->fname . ' ' . $tutor->user->lname }}</p>
                <x-wui-icon name='badge-check' class="size-4 text-[#292D32]" solid />
            </div>
            <div class="flex flex-col gap-1">
                <div class="inline-flex items-center gap-1">
                    <x-wui-icon name='star' class="size-4 text-[#64748B]" solid />
                    <span class="text-xs text-[#64748B]">
                        @if ($tutor->average_rating == 5.0)
                            5/5
                        @elseif ($tutor->average_rating == 0.0)
                            Not rated yet
                        @else
                            {{ $tutor->average_rating }}/5
                        @endif
                    </span>
                </div>
                <div class="inline-flex items-center gap-1">
                    <x-wui-icon name='academic-cap' class="size-4 text-[#0F172A]" solid />
                    <span class="text-xs hover:underline text-[#0F172A]">
                        View Portfolio
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
