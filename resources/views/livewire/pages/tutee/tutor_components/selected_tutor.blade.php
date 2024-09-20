@php
    use App\Models\Fields;
    use Carbon\Carbon;
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
                        <svg class="size-5" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3 2.5C3 2.22386 3.22386 2 3.5 2H11.5C11.7761 2 12 2.22386 12 2.5V13.5C12 13.6818 11.9014 13.8492 11.7424 13.9373C11.5834 14.0254 11.3891 14.0203 11.235 13.924L7.5 11.5896L3.765 13.924C3.61087 14.0203 3.41659 14.0254 3.25762 13.9373C3.09864 13.8492 3 13.6818 3 13.5V2.5ZM4 3V12.5979L6.97 10.7416C7.29427 10.539 7.70573 10.539 8.03 10.7416L11 12.5979V3H4Z"
                                fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
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
            @if (in_array($item->field_name, $class_fields))
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
                                <x-secondary-button class="w-full lg:w-fit text-nowrap">Join Class</x-secondary-button>
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

        {{-- parent div --}}
        <div class="flex items-start gap-3">
            <img class="rounded-full size-10" src="{{ asset('images/default.jpg') }}">
            <div class="flex flex-col w-full space-y-2">
                {{-- reviewer's name --}}
                <div class="inline-flex gap-2 text-sm">
                    <p class="font-semibold">
                        Santiago López
                    </p>
                    <span class="text-[#64748B]">rated 6.5/10</span>
                </div>

                {{-- reviewer's description --}}
                <div x-data="{ isCollapsed: true }">
                    <div :class="isCollapsed ? 'line-clamp-2 max-h-12' : 'max-h-auto'" class="overflow-hidden">
                        Miss Helen is an outstanding data structure teacher! Her clear explanations and
                        engaging teaching style made complex concepts easy to understand. She is
                        patient, approachable, and always willing to help students grasp the intricacies
                        of data structures. A truly excellent educator!
                    </div>

                    <button @click="isCollapsed = !isCollapsed" class="mt-2 text-xs underline">
                        <span x-text="isCollapsed ? 'Read More' : 'Show Less'"></span>
                    </button>
                </div>

                {{-- review date --}}
                <div class="text-[#64748B] inline-flex gap-2 items-center">
                    <x-wui-icon name='calendar' class="size-5" />
                    <p class="font-light text-sm">
                        Posted on January 2, 2024
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>