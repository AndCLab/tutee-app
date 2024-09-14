<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Classes;
use App\Models\Fields;

new #[Layout('layouts.app')] class extends Component {

    public $classes;

    public function mount()
    {
        $user = Auth::user();
        $tutor = Tutor::where('user_id', $user->id)->first();

        $this->classes = Classes::where('tutor_id', $tutor->id)->get();

        // foreach ($this->classes as $value) {
        //     foreach ($value->schedule->recurring_schedule as $recurring) {
        //         dd($recurring->dates);
        //     }
        // }

    }


}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">

        <div class="md:grid md:grid-row items-start gap-5 pb-3">
            <p class="capitalize font-semibold text-xl">classes</p>
        </div>

        {{-- schedule card --}}
        <div class="space-y-2 mt-4">
            @forelse ($classes as $count => $class)
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
                                    @foreach ($class->schedule->recurring_schedule as $recurring)
                                        {{ Carbon::create($recurring->dates)->format('l jS \\of F Y g:i A') }}
                                    @endforeach
                                </p>
                            </div>
                            @if ($class->class_category == 'group')
                                <div>
                                    <x-primary-button wire:navigate href="{{ route('view-students', $class->id) }}" class="w-full lg:w-fit text-nowrap">
                                        View Students
                                    </x-primary-button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex justify-between items-end p-4 rounded border">
                    No Schedule Yet
                </div>
            @endforelse
        </div>
    </div>
</section>
