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

    public function mount(int $id)
    {
        $this->cr_id = $id;
        $this->class = Classes::findOrFail($id);
        $this->class_roster = ClassRoster::where('class_id', $id)->get();
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
        $newPaymentStatus = ($this->attachment_id->payment_status === 'Not Approved' ||
                            $this->attachment_id->payment_status === 'Pending') ? 'Approved' : 'Not Approved';

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

        <div class="md:grid md:grid-row items-start pb-3">
            <p class="capitalize font-semibold text-xl">List of Attendees</p>
            <p class="capitalize text-md text-[#0F172A]">Class Name: {{ $class->class_name }}</p>
            <p class="capitalize text-md text-[#0F172A]">Schedule: {{
                Carbon::create($class->schedule->start_time)->format('g:iA') . ' - ' .
                Carbon::create($class->schedule->end_time)->format('g:iA l')
            }}</p>
            <p class="capitalize text-md text-[#0F172A]">Total Students: {{ $total_students }}</p>
        </div>
        <table>
            <thead>
                <th>Name</th>
                <th></th>
            </thead>
        </table>
        @forelse ($class_roster as $roster)
            {{ $tutee->user->fname }}
        @empty
            Empty
        @endforelse
    </div>
</section>
