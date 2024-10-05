<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use WireUi\Traits\Actions;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Carbon\Carbon;
use App\Models\Tutor;
use App\Models\Classes;
use App\Models\Schedule;
use App\Models\Registration;
use App\Models\Fields;

new #[Layout('layouts.app')] class extends Component {
    use Actions;

    // get auth tutor
    public $tutor;

    // class properties
    public string $class_name = '';
    public string $class_description = '';
    public string $class_type = '';
    public string $class_location = '';
    public string $class_category = '';
    public string $class_link = '';
    public $class_students;
    public $class_fee = 0;
    public $class_fields = []; //fields setter
    public $getFields = []; // fields getter

    // for class schedule date
    public $sched_start_date;
    public $sched_end_date;

    // for registration date
    public $regi_start_date;
    public $regi_end_date;

    public $classes;

    // states
    #[Url(as: 'search_class')]
    public $search = '';

    #[Url(as: 'sort_by')]
    public $sort_by;

    #[Url(as: 'class_status')]
    public $class_status;

    public bool $isEmptyClass = false;
    public $showWithdrawClassModal;
    public $deleteClassId;

    public function mount()
    {
        // default value: descending
        $this->sort_by = 'desc';
        $this->class_status = '1';
        $this->tutor = Tutor::where('user_id', Auth::id())->first();

        if ($this->tutor) {
            $this->classes = Classes::where('tutor_id', $this->tutor->id)
                                    ->where('class_status', 1)
                                    ->orderBy('created_at', $this->sort_by)
                                    ->get();
        }

        $this->getFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])
                                ->toArray();

        // boolean for checking if the collection is empty
        $this->isEmptyClass = $this->classes->isEmpty();
    }


    #[On('new-class')]
    public function updateList($isNotEmpty)
    {
        if ($this->isEmptyClass != $isNotEmpty) {
            $this->isEmptyClass = $isNotEmpty;
        }

        $this->classes = Classes::where('tutor_id', $this->tutor->id)
                                ->where('class_status', $this->class_status)
                                ->orderBy('created_at', $this->sort_by)
                                ->get();
    }

    /* start filter functions */
    public function updated()
    {
        $this->classes = Classes::when($this->search, function ($q) {
                                $q->where('class_name', 'like', "%{$this->search}%");
                            })
                            ->when($this->sort_by, function ($q) {
                                $q->orderBy('created_at', $this->sort_by);
                            })
                            ->when($this->class_status && $this->class_status !== null, function ($q) {
                                $q->where('class_status', $this->class_status);
                            })
                            ->get();
    }
    /* end filter functions */

    // delete class
    public function withdrawClass()
    {
        $class = Classes::find($this->deleteClassId);
        $sched = $class->schedule;
        $regi = $class->registration;

        $currentFields = json_decode($class->class_fields);

        foreach ($currentFields as $classField) {
            $fields = Fields::where('user_id', Auth::id())
                            ->where('active_in', Auth::user()->user_type)
                            ->where('field_name', $classField)
                            ->get();

            foreach ($fields as $field) {
                $field->class_count = $field->class_count - 1;
                $field->save();
            }
        }

        if ($sched) {
            $sched->delete();
        }

        if ($regi) {
            $regi->delete();
        }

        $this->notification([
            'title' => 'Removed',
            'description' => 'Successfully remove class',
            'icon' => 'success',
            'timeout' => 2500,
        ]);

        $this->showWithdrawClassModal = false;
        $this->mount();
    }

    // trigger withdraw class modal
    public function withdrawClassModal($classId)
    {
        $this->showWithdrawClassModal = true;
        $this->deleteClassId = $classId;
    }
}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <p class="capitalize font-semibold text-xl mb-9">class list</p>

    {{-- Class List: Search and Filter --}}
    <div class="flex flex-wrap md:flex-nowrap gap-2 pb-3">
        <div class="w-full md:w-2/4">
            <x-wui-input wire:model.live='search' placeholder='Search class...' icon='search' shadowless />
        </div>
        <div class="w-full md:w-fit">
            <x-wui-select wire:model.live='sort_by' placeholder="Sort by" shadowless>
                <x-wui-select.option label="Ascending" value="asc" />
                <x-wui-select.option label="Descending" value="desc" />
            </x-wui-select>
        </div>
        <div class="w-full md:w-fit">
            <x-wui-select wire:model.live='class_status' placeholder="Class Status" shadowless>
                <x-wui-select.option label="All Classes" value="0" />
                <x-wui-select.option label="Pending Classes" value="1" />
            </x-wui-select>
        </div>
    </div>

    {{-- Class List: Class Cards --}}
    <div class="space-y-3">
        @forelse ($classes as $class)
            <div class="w-full bg-[#F1F5F9] p-4 pb-2 rounded-md text-[#0F172A] space-y-4" wire:loading.remove>
                <div class="space-y-1">
                    <div class="flex justify-between items-center">
                        <div class="inline-flex items-center gap-2">
                            <p class="font-semibold">{{ $class->class_name }}</p>
                            @if ($class->class_category == 'group')
                                <x-wui-badge flat warning label="{{ $class->class_category }}" />
                            @else
                                <x-wui-badge flat purple label="{{ $class->class_category }}" />
                            @endif
                        </div>
                        @if ($class->class_status == 1)
                            <x-wui-dropdown>
                                <x-wui-dropdown.header class="font-semibold" label="Actions">
                                    <x-wui-dropdown.item wire:navigate href="{{ route('edit-class', $class->id) }}"
                                        icon='pencil-alt' label="Edit" />
                                    <x-wui-dropdown.item wire:click='withdrawClassModal({{ $class->id }})'
                                        icon='trash' label="Withdraw" />
                                </x-wui-dropdown.header>
                            </x-wui-dropdown>
                        @endif
                    </div>
                    <div class="flex gap-2 items-center text-[#64748B] text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p>Created at {{ $class->created_at->format('l jS \\of F Y h:i A') }}</p>
                    </div>
                </div>
                <p class="line-clamp-3 antialiased leading-snug">
                    {{ $class->class_description }}
                </p>
                <div x-data="{ expanded: false }">
                    <div class="text-sm p-3 rounded-md bg-[#E1E7EC]" x-show="expanded" x-collapse x-cloak>
                        <p>
                            <strong>Class Status:</strong> {{ $class->class_status == 1 ? 'Open' : 'Closed' }}
                        </p>
                        <p>
                            <strong>Class Type:</strong> {{ ucfirst($class->class_type) }}
                        </p>
                        <p>
                            <strong>Class Fee:</strong> {{ $class->class_fee == 0.0 ? 'Free Class' : number_format($class->class_fee, 2) }}
                        </p>
                        @if ($class->class_students >= 2)
                            <p>
                                There are {{ $class->class_students }} slots available
                            </p>
                        @endif
                        <p>
                            <strong>Class Fields:</strong>
                            {{ implode(', ', json_decode($class->class_fields, true)) }}
                        </p>
                        <p>
                            <strong>Class Location:</strong> {{ $class->class_location }}
                        </p>
                        <p>
                            <strong>Starts at:</strong>
                            @foreach ($class->schedule->recurring_schedule as $recurring)
                                {{ Carbon::create($recurring->dates)->format('l jS \\of F Y') }}
                            @endforeach
                        </p>
                        <p>
                            <strong>Time:</strong>
                            {{ Carbon::create($class->schedule->start_time)->format('g:i A') }} -
                            {{ Carbon::create($class->schedule->end_time)->format('g:i A') }}
                        </p>
                    </div>


                    <div class="flex justify-end">
                        <template x-if='expanded == false' x-transition>
                            <x-wui-button @click="expanded = ! expanded" xs label='View more' icon='arrow-down'
                                flat />
                        </template>
                        <template x-if='expanded == true' x-transition>
                            <x-wui-button @click="expanded = ! expanded" xs label='View less' icon='arrow-up'
                                flat />
                        </template>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col gap-3 justify-center items-center w-full" wire:loading.remove>
                <img class="size-60" src="{{ asset('images/empty_class.svg') }}" alt="">
                <p class="font-semibold text-xl">No classes</p>
            </div>
        @endforelse
    </div>

    <x-wui-modal wire:model="showWithdrawClassModal" persistent align='center' max-width='sm'>
        <x-wui-card title="Delete Class">
            <p class="text-gray-600">
                Do you wanna remove this class?
                <span class="font-semibold">
                    {{ Classes::where('id', $deleteClassId)->pluck('class_name')->first() }}
                </span>
            </p>

            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-wui-button flat label="Cancel" x-on:click="close" />
                    <x-wui-button wire:click='withdrawClass' spinner='withdrawClass' negative label="Yes, Delete it" />
                </div>
            </x-slot>
        </x-wui-card>
    </x-wui-modal>

    <x-class-skeleton loadClass='search' />
    <x-class-skeleton loadClass='sort_by' />
    <x-class-skeleton loadClass='class_status' />
    <x-class-skeleton loadClass='withdrawClass' />
    <x-class-skeleton loadClass='withdrawClassModal' />

</section>
