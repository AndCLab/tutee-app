@if ($attendees)
    <x-wui-modal.card title="View Attendees" align='center' max-width='md' blur wire:model="view_attendees">
        <div class="grid grid-row-1 sm:grid-row-2 space-y-2">
            @foreach ($attendees as $attendee)
                <div class="text-sm p-3 rounded-md bg-[#F1F5F9]">
                    <div class="flex flex-wrap gap-3 items-start">
                        @if ($attendee->tutees->user->avatar !== null)
                            <img class="border-2 border-[#F1F5F9] overflow-hidden rounded-md size-14"
                                src="{{ Storage::url($attendee->tutees->user->avatar) }}">
                        @else
                            <img class="border-2 border-[#F1F5F9] overflow-hidden rounded-md size-14"
                                src="{{ asset('images/default.jpg') }}">
                        @endif
                        <div class="flex flex-col space-y-1">
                            <div class="inline-flex items-center gap-1">
                                <p class="text-sm font-medium">{{ $attendee->tutees->user->fname . ' ' . $attendee->tutees->user->lname }}</p>
                            </div>
                            <div class="flex flex-col gap-1">
                                <div class="inline-flex items-center gap-1">
                                    <x-wui-icon name='academic-cap' class="size-4 text-[#64748B]" solid />
                                    <span class="text-xs text-[#64748B]">
                                        {{ $attendee->tutees->grade_level }}
                                    </span>
                                </div>
                                <div class="inline-flex items-center gap-1">
                                    <x-wui-icon name='at-symbol' class="size-4 text-[#64748B]" solid />
                                    <span class="text-xs text-[#64748B]">
                                        {{ $attendee->tutees->user->email }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <x-slot name="footer">
            <x-wui-button class="w-full" flat label="Cancel" x-on:click="close" />
        </x-slot>
    </x-wui-modal.card>
@endif
