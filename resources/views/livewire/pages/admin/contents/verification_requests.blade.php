<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Classes;
use App\Models\Fields;

new #[Layout('layouts.admin')] class extends Component {
    use WithPagination;

    public $title = 'Verification Requests | Tutee';

    // datatable properties
    #[Url(as: 'search_student')]
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $selected = [];
    public $selectAll = false;
    public $perPage = 5;

    // properties
    public $getTutor;
    public $indi_class_count;
    public $group_class_count;
    public $filter_status = 'pending';

    // states
    public $showTutorDetailsModal;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // $value is the value of the public property itself
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getDataQuery()->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function approveTutor($value)
    {
        $tutors = Tutor::whereIn('id', $this->selected)->get();

        foreach ($tutors as $tutor) {
            if(!($tutor->verify_status === $value)) {
                $tutor->verify_status = $value;
                $tutor->save();
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

    public function viewTutorDetails($tutorId)
    {
        $this->showTutorDetailsModal = true;
        $this->getTutor = Tutor::findOrFail($tutorId);

        $this->indi_class_count = Classes::where('tutor_id', $this->getTutor->id)->where('class_category', 'individual')->count();
        $this->group_class_count = Classes::where('tutor_id', $this->getTutor->id)->where('class_category', 'group')->count();
    }

    public function with(): array
    {
        return [
            'tutors' => $this->getDataQuery(),
        ];
    }

    // made a function for the eloquent query to avoid code repetition
    public function getDataQuery()
    {
        return Tutor::query()
                    ->select('tutor.*')  // select all class_roster fields
                    ->join('users', 'tutor.user_id', '=', 'users.id')  // join users through tutees
                    ->where('verify_status', $this->filter_status)

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

        <div>
            {{-- search filter --}}
            <div class="md:grid md:grid-row items-start gap-5">
                <p class="capitalize font-semibold text-xl">Verification Requests</p>
                <div class="sm:inline-flex sm:justify-between sm:items-center mb-4 w-full">
                    <div class="sm:inline-flex sm:items-center gap-2 space-y-2 sm:space-y-0">
                        <div class="w-full sm:w-2/4">
                            <x-wui-input placeholder='Search a student...' wire:model.live='search' shadowless/>
                        </div>
                        {{-- sort by --}}
                        <div>
                            <x-wui-select wire:model.live='filter_status' placeholder="Sort by" shadowless>
                                <x-wui-select.option label="Pending" value="pending" />
                                <x-wui-select.option label="Verified" value="verified" />
                            </x-wui-select>
                        </div>
                    </div>


                    @if ($selected || $selectAll)
                        <div class="mt-2 sm:mt-0">
                            <x-wui-dropdown class="w-full">
                                <x-slot name="trigger">
                                    <x-wui-button label="Approve Tutor" flat green sm icon='clipboard-check'/>
                                </x-slot>

                                <x-wui-dropdown.item label="Approve" wire:click="approveTutor('verified')"/>
                                <x-wui-dropdown.item label="Disapprove" wire:click="approveTutor('not_verified')"/>
                            </x-wui-dropdown>
                        </div>
                    @endif
                </div>
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
                                'name' => 'id',
                                'displayName' => 'Tutor ID'
                            ])

                            @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                'name' => 'lname',
                                'displayName' => 'Full Name'
                            ])

                            @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                'name' => 'email',
                                'displayName' => 'Email Address'
                            ])

                            @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                'name' => 'verify_status',
                                'displayName' => 'Approval Status'
                            ])

                            <th class="text-end whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse($tutors as $tutor)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    <x-wui-checkbox id="selected" value="{{ $tutor->id }}" wire:model.live="selected"/>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    {{ $tutor->id }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    {{ $tutor->user->lname }},
                                    {{ $tutor->user->fname }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $tutor->user->email }}</td>
                                <td class="whitespace-nowrap px-4 py-1 text-gray-700">
                                    <div @class([
                                        'text-[#198754]' => $tutor->verify_status === 'verified',
                                        'text-[#0F172A]' => $tutor->verify_status === 'pending',
                                    ])>
                                        <p class="flex gap-1 items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            {{ ucfirst($tutor->verify_status) }}
                                        </p>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-1 text-gray-700">
                                    <x-wui-button slate xs class="w-full" label="View Info" wire:click="viewTutorDetails({{ $tutor->id }})" spinner="viewTutorDetails({{ $tutor->id }})"/>
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
                {{ $tutors->links() }}
            </div>
        </div>

        {{-- modal --}}
        @if ($getTutor)
            <x-wui-modal.card title="Tutor Details" wire:model="showTutorDetailsModal" persistent max-width='2xl'>
                <div class="grid grid-row-1 sm:grid-row-2 gap-4">

                    {{-- profile and degree --}}
                    <div>
                        <div class="flex flex-wrap items-center gap-4">
                            @if ($getTutor->user->avatar !== null)
                                <img class="rounded-md size-24" src="{{ Storage::url($getTutor->user->avatar) }}">
                            @else
                                <img class="rounded-md size-24" src="{{ asset('images/default.jpg') }}">
                            @endif
                            <div>
                                <div class="flex gap-2 items-center">
                                    <h2 class="text-xl font-semibold truncate">
                                        {{ $getTutor->user->fname . ' ' . $getTutor->user->lname}}
                                    </h2>
                                </div>
                                <div class="inline-flex items-center gap-1">
                                    <x-wui-icon name='academic-cap' class="size-4 text-[#64748B]" solid />
                                    <span class="text-xs text-[#64748B]">
                                        @php
                                            $degrees = json_decode($getTutor->degree, true);
                                        @endphp
                                        {{ is_array($degrees) ? implode(', ', $degrees) : $getTutor->degree }}
                                    </span>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <x-wui-badge class="w-fit" icon='users' flat warning
                                        label="{{ $group_class_count }} Group Classes" />
                                    <x-wui-badge class="w-fit" icon='user' flat purple
                                        label="{{ $indi_class_count }} Individual Classes" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- bio --}}
                    <div class="space-y-2">
                        <h2 class="text-lg font-semibold">{{ $getTutor->user->fname }}'s Bio</h2>
                        {{ $getTutor->bio }}
                        @empty($getTutor->bio)
                            <div class="flex justify-between items-end p-4 rounded border">
                                {{ $getTutor->user->fname }} has no Bio
                            </div>
                        @endempty
                    </p>

                    {{-- exp --}}
                    <div class="space-y-2">
                        <h2 class="text-lg font-semibold">{{ $getTutor->user->fname }}'s Work Experience</h2>

                        @forelse ($getTutor->works as $work)
                            <div class="flex flex-col gap-1">
                                <p>
                                    Gained Experience from the Company: {{ $work->company }}
                                    <x-wui-badge sm info label="{{ Carbon::parse($work->from)->format('Y') . ' - ' . Carbon::parse($work->to)->format('Y') }}"/>
                                </p>
                            </div>
                        @empty
                            <div class="flex justify-between items-end p-4 rounded border">
                                {{ $getTutor->user->fname }} has no Work Experiences
                            </div>
                        @endforelse
                    </p>

                    {{-- fields card --}}
                    <div class="space-y-2 mt-4">
                        <h2 class="text-lg font-semibold">{{ $getTutor->user->fname }}'s Fields</h2>

                        @forelse ($fields = Fields::where('user_id', $getTutor->user->id)->get() as $index => $item)
                            <x-wui-badge flat slate label="{{ $item->field_name }}" />
                        @empty
                            <div class="flex justify-between items-end p-4 rounded border">
                                {{ $getTutor->user->fname }} has no Fields
                            </div>
                        @endforelse
                    </div>

                    {{-- certificates --}}
                    <div class="space-y-2 mt-4">
                        <h2 class="text-lg font-semibold">{{ $getTutor->user->fname }}'s Certificates</h2>

                        @forelse ($getTutor->certificates as $index => $certificate)
                            <div class="flex flex-col gap-1">
                                <p>
                                    Certificate {{ $index + 1 }}: {{ $certificate->title }}
                                    <x-wui-badge sm info label="{{ Carbon::parse($certificate->from)->format('Y') }}"/>
                                </p>
                            </div>
                            @if (pathinfo($certificate->file_path, PATHINFO_EXTENSION) !== 'pdf')
                                <img src="{{ Storage::url($certificate->file_path) }}" alt="">
                            @else
                                <embed src="{{ Storage::url($certificate->file_path) }}" width="100%" height="100%" type="application/pdf">
                            @endif
                        @empty
                            <div class="flex justify-between items-end p-4 rounded border">
                                {{ $getTutor->user->fname }} has no Certificates
                            </div>
                        @endforelse
                    </div>

                    {{-- resume --}}
                    <div class="space-y-2 mt-4">
                        <h2 class="text-lg font-semibold">{{ $getTutor->user->fname }}'s Resume</h2>

                        @if ($getTutor->resume)
                            @if (pathinfo($getTutor->resume->file_path, PATHINFO_EXTENSION) !== 'pdf')
                                <img src="{{ Storage::url($getTutor->resume->file_path) }}" alt="">
                            @else
                                <embed src="{{ Storage::url($getTutor->resume->file_path) }}" width="100%" height="100%" type="application/pdf">
                            @endif
                        @else
                            <div class="flex justify-between items-end p-4 rounded border">
                                {{ $getTutor->user->fname }} has no resume
                            </div>
                        @endif
                    </div>
                </div>
            </x-wui-modal.card>
        @endif

    </div>
</section>
