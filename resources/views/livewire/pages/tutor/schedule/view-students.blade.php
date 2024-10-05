<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Classes;
use App\Models\ClassRoster;
use App\Models\Fields;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $title = 'View Class | Tutee';

    // datatable properties
    #[Url(as: 'search_student')]
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $selected = [];
    public $selectAll = false;
    public $perPage = 5;
    public $attachment = false;

    // class roster properties
    public $total_students;
    public $class;
    public $attachment_id;

    public function mount(int $id)
    {
        $this->class = Classes::findOrFail($id);
        $this->total_students = ClassRoster::where('class_id', $id)->count();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // $value is the value of the public property itself
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getDataQuery()->pluck('tutee_id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function attendanceCheck($value)
    {
        $students = ClassRoster::whereIn('tutee_id', $this->selected)->get();

        foreach ($students as $stud) {
            if(!($stud->attendance === $value)) {
                $newAttendance = ($stud->attendance === 'Absent' || $stud->attendance === 'Pending') ? 'Present' : 'Absent';

                $stud->attendance = $newAttendance;
                $stud->save();
            }
        }

        $this->selected = [];
        $this->selectAll = false;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function showAttachment($id)
    {
        $this->attachment_id = ClassRoster::find($id);
        $this->attachment = true;
    }

    public function verifyPayment()
    {
        $newPaymentStatus = ($this->attachment_id->payment_status === 'Approved' ||
                            $this->attachment_id->payment_status === 'Pending') ? 'Not Approved' : 'Approved';

        $this->attachment_id->payment_status = $newPaymentStatus;
        $this->attachment_id->save();

        $this->attachment = false;
    }

    public function with(): array
    {
        return [
            'class_roster' => $this->getDataQuery(),
        ];
    }

    // made a function for the eloquent query to avoid code repetition
    public function getDataQuery()
    {
        return ClassRoster::query()
                        ->select('class_rosters.*')  // select all class_roster fields
                        ->join('tutee', 'class_rosters.tutee_id', '=', 'tutee.id')  // join tutee
                        ->join('users', 'tutee.user_id', '=', 'users.id')  // join users through tutees
                        ->where('class_rosters.class_id', $this->class->id)

                        ->search($this->search)
                        ->when($this->sortField, function ($query) {
                            $query->orderBy($this->sortField, $this->sortDirection);
                        })
                        ->paginate($this->perPage);
    }

}; ?>

@push('title')
    {{ $title }}
@endpush

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">

        {{-- class details --}}
        <div class="md:grid md:grid-row items-start pb-6">
            <div class="w-full flex justify-between">
                <p class="capitalize font-semibold text-xl">List of Attendees</p>
                <x-primary-button href="{{ route('tutor.schedule') }}" wire:navigate>
                    Return to Schedule
                </x-primary-button>
            </div>
            <p class="capitalize text-sm text-[#0F172A]">Class Name: {{ $class->class_name }}</p>
            <p class="capitalize text-sm text-[#0F172A]">Schedule: {{
                Carbon::create($class->schedule->start_time)->format('g:iA') . ' - ' .
                Carbon::create($class->schedule->end_time)->format('g:iA l')
            }}</p>
            <p class="capitalize text-sm text-[#0F172A]">Total Students: {{ $total_students }}</p>
        </div>

        <div>
            {{-- search filter --}}
            <div class="sm:inline-flex sm:justify-between sm:items-center mb-4 w-full">
                <div class="w-full sm:w-2/6">
                    <x-wui-input placeholder='Search a student...' wire:model.live='search' shadowless/>
                </div>
                @if ($selected || $selectAll)
                    <div class="mt-2 sm:mt-0">
                        <x-wui-dropdown class="w-full">
                            <x-slot name="trigger">
                                <x-wui-button label="Check Attendance" flat green sm icon='clipboard-check'/>
                            </x-slot>

                            <x-wui-dropdown.item label="Mark as Present" wire:click="attendanceCheck('Present')"/>
                            <x-wui-dropdown.item label="Mark as Absent" wire:click="attendanceCheck('Absent')"/>
                        </x-wui-dropdown>
                    </div>
                @endif
            </div>

            {{-- table --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200 soft-scrollbar">
                <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm" wire:loading.class='opacity-60'>
                    <thead>
                        <tr>
                            <th class="text-start whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                <x-wui-checkbox id="selectAll" wire:model.live="selectAll" />
                            </th>
                            @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                'name' => 'lname',
                                'displayName' => 'Student Full Name'
                            ])

                            @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                'name' => 'email',
                                'displayName' => 'Student Email'
                            ])

                            @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                'name' => 'proof_of_payment',
                                'displayName' => 'Proof of Payment'
                            ])

                            @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                'name' => 'payment_status',
                                'displayName' => 'Payment Status'
                            ])

                            @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                'name' => 'attendance',
                                'displayName' => 'Attendance'
                            ])
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse($class_roster as $roster)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    <x-wui-checkbox id="selected" value="{{ $roster->tutee_id }}" wire:model.live="selected"/>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    {{ $roster->tutees->user->lname }},
                                    {{ $roster->tutees->user->fname }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $roster->tutees->user->email }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                                    <button class="cursor-pointer underline underline-offset-2 text-[#0C3B2E]"
                                            wire:click='showAttachment({{ $roster->id }})'>
                                                View Attachment
                                    </button>
                                </td>
                                <td class="whitespace-nowrap px-4 py-1 text-gray-700">
                                    <div @class([
                                        'text-[#198754]' => $roster->payment_status === 'Approved',
                                        'text-[#871919]' => $roster->payment_status === 'Not Approved',
                                    ])>
                                        <p class="flex gap-1 items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $roster->payment_status }}
                                        </p>
                                    </div>
                                </td>
                                <td @class(['whitespace-nowrap px-4 py-2 text-gray-700',
                                        'text-green-700' => $roster->attendance === 'Present',
                                        'text-red-700' => $roster->attendance === 'Absent',
                                ])>
                                    {{ $roster->attendance }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="whitespace-nowrap px-4 py-2 text-gray-700 text-center">
                                    <span class="font-semibold text-xl antialiased">
                                        Empty
                                    </span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- pagination --}}
            <div class="mt-4">
                <div class="w-full sm:w-[7rem] mb-4 sm:mb-0">
                    <x-wui-select
                        placeholder='Per Page'
                        wire:model.live="perPage"
                        shadowless
                    >
                        <x-wui-select.option label="5" value="5" />
                        <x-wui-select.option label="10" value="10" />
                        <x-wui-select.option label="20" value="20" />
                    </x-wui-select>
                </div>
                {{ $class_roster->links() }}
            </div>
        </div>

        {{-- modal --}}
        @if ($attachment_id)
            <x-wui-modal.card title="Proof of Payment" blur wire:model="attachment" persistent align='center' max-width='sm'>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{ $attachment_id->tutee_id }}
                </div>

                <x-slot name="footer">
                    @if ($attachment_id->payment_status === 'Approved')
                        <x-wui-button negative label="Disapprove" spinner='verifyPayment' wire:click='verifyPayment' class="w-full"/>
                    @else
                        <x-primary-button wire:click='verifyPayment' wireTarget='verifyPayment' class="w-full">Verify Payment</x-primary-button>
                    @endif
                </x-slot>
            </x-wui-modal.card>
        @endif

    </div>
</section>
