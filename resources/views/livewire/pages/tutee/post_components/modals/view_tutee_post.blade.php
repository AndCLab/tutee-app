@php
    use Carbon\Carbon;
@endphp

@if ($getPost)
    <x-wui-modal.card wire:model="showTuteePost" class="space-y-3" align='center' max-width='xl'>
        <div class="flex gap-2 items-start">
            <div class="size-16">
                <img
                    alt="User Avatar"
                    src="{{ $getPost->tutees->user->avatar ? Storage::url($getPost->tutees->user->avatar) : asset('images/default.jpg') }}"
                    class="rounded-full object-cover border border-[#F1F5F9] overflow-hidden"
                />
            </div>
            <div class="w-full space-y-2">
                <p class="flex gap-2 font-semibold">
                    {{ $getPost->tutees->user->fname .' '. $getPost->tutees->user->lname}}
                </p>
                <div class="inline-flex items-center gap-1">
                    <x-wui-icon name='academic-cap' class="size-4 text-[#64748B]" solid />
                    <p class="font-light text-xs">
                        {{ $getPost->tutees->grade_level }}
                    </p>
                </div>
            </div>
        </div>

        {{-- post description --}}
        <div class="flex flex-col font-semibold">
            <div class="flex gap-2 items-center">
                About the Class
                <div>
                    @if ($getPost->class_category == 'group')
                        <x-wui-badge flat warning label="{{ $getPost->class_category }}" />
                    @else
                        <x-wui-badge flat purple label="{{ $getPost->class_category }}" />
                    @endif
                </div>
            </div>
            <span class="font-light">
                {{ $getPost->post_desc}}
            </span>
        </div>

        {{-- collapsable class details --}}
        <div class="p-3 py-2 rounded-md bg-[#E1E7EC]">
            <span class="font-semibold text-sm">Post Details</span>
            <div class="text-sm space-y-1">
                <p>
                    <strong>Desired Date:</strong> {{ Carbon::parse($getPost->class_date)->format('l, F d Y g:i A') }}
                </p>
                <p>
                    <strong>Estimated Fee:</strong> {{ $getPost->class_fee == 0.0 ? 'Free Class' : number_format($getPost->class_fee, 2) }}
                </p>
                <p>
                    <strong>Class Type:</strong> {{ ucfirst($getPost->class_type) }}
                </p>
                @if ($getPost->class_location && $getPost->class_type == 'physical')
                    <p>
                    <strong>Class Location:</strong> {{ $getPost->class_location }}
                    </p>
                @endif
            </div>
        </div>

        <x-slot name='footer'>
            <x-tertiary-button class="w-full inline-flex gap-2 items-center justify-center">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"
                    fill="#292D32"  class="icon icon-tabler icons-tabler-filled icon-tabler-message">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M18 3a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-4.724l-4.762 2.857a1 1 0 0 1 -1.508 -.743l-.006 -.114v-2h-1a4 4 0 0 1 -3.995 -3.8l-.005 -.2v-8a4 4 0 0 1 4 -4zm-4 9h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m2 -4h-8a1 1 0 1 0 0 2h8a1 1 0 0 0 0 -2" />
                </svg>
                <p>
                    Message {{ $getPost->tutees->user->fname }}
                </p>
            </x-tertiary-button>
            <div class="flex items-center justify-between font-light text-xs pt-2">
                <div class="flex gap-2 items-center text-[#64748B]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p>Posted on {{ $getPost->created_at->format('l, F d Y g:i A') }}</p>
                </div>
                <x-wui-button label="Report Content" flat xs icon="exclamation" />
            </div>
        </x-slot>
    </x-wui-modal.card>
@endif
