<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Models\ReportContent;

new #[Layout('layouts.admin')] class extends Component {
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $selected = [];
    public $selectAll = false;
    public $perPage = 5;

    public $reportOptions;
    public $report_options = [];
    public $availableOptions = [];
    public $filter_status = 'pending';

    public $viewContentModal = false;
    public $getContent;

    public function mount()
    {
        $this->availableOptions = ReportContent::distinct()
            ->pluck('report_option')
            ->toArray();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getDataQuery()->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function reportContentStatus($value)
    {
        $report_contents = ReportContent::whereIn('id', $this->selected)->get();

        foreach ($report_contents as $report_content) {
            if(!($report_content->status === $value)) {
                $report_content->status = $value;
                $report_content->save();
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

    public function with(): array
    {
        return [
            'report_contents' => $this->getDataQuery(),
        ];
    }

    public function getDataQuery()
    {
        return ReportContent::query()
            ->select('report_contents.*')
            ->join('users', 'report_contents.reporter_id', '=', 'users.id')

            ->search($this->search)
            ->where('status', $this->filter_status)
            ->when($this->sortField, function ($query) {
                $query->orderBy($this->sortField, $this->sortDirection);
            })
            ->when($this->report_options, function ($query) {
                $query->whereIn('report_option', $this->report_options);
            })
            ->paginate($this->perPage);
    }

    public function openContentModal($id)
    {
        $this->viewContentModal = true;
        $this->getContent = ReportContent::findOrFail($id);
    }
}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">

            <div>
                {{-- search filter --}}
                <div class="md:grid md:grid-row items-start gap-5">
                    <p class="capitalize font-semibold text-xl">Content Moderation</p>
                    <div class="sm:inline-flex sm:justify-between sm:items-center mb-4 w-full">
                        <div class="sm:inline-flex sm:items-center gap-2 space-y-2 sm:space-y-0">
                            {{-- Search content... --}}
                            <div class="w-full sm:w-2/6">
                                <x-wui-input placeholder='Search a report content...' wire:model.live='search' shadowless/>
                            </div>

                            {{-- Report Option --}}
                            <div class="w-full sm:w-2/6">
                                <x-wui-select
                                    wire:model.live="report_options" placeholder="Select Report Option/s" multiselect shadowless>
                                    @foreach ($availableOptions as $option)
                                        <x-wui-select.option label="{{ $option }}"
                                            value="{{ $option }}" />
                                    @endforeach
                                </x-wui-select>
                            </div>

                            {{-- filter by --}}
                            <div>
                                <x-wui-select wire:model.live='filter_status' placeholder="Filter by" shadowless>
                                    <x-wui-select.option label="Pending" value="pending" />
                                    <x-wui-select.option label="Approved" value="approved" />
                                    <x-wui-select.option label="Disapproved" value="not approved" />
                                </x-wui-select>
                            </div>
                        </div>

                        @if ($selected || $selectAll)
                            <div class="mt-2 sm:mt-0">
                                <x-wui-dropdown class="w-full">
                                    <x-slot name="trigger">
                                        <x-wui-button label="Update Status" flat green sm icon='clipboard-check'/>
                                    </x-slot>

                                    <x-wui-dropdown.item label="Approved" wire:click="reportContentStatus('Approved')"/>
                                    <x-wui-dropdown.item label="Not Approved" wire:click="reportContentStatus('Not Approved')"/>
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
                                    'name' => 'lname',
                                    'displayName' => 'Reporter'
                                ])

                                @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                    'name' => 'lname',
                                    'displayName' => 'Reported'
                                ])

                                @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                    'name' => 'date_reported',
                                    'displayName' => 'Reported Date'
                                ])

                                @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                    'name' => 'report_option',
                                    'displayName' => 'Report Option'
                                ])

                                @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                    'name' => 'status',
                                    'displayName' => 'Status'
                                ])

                                @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                    'name' => 'action',
                                    'displayName' => 'Action'
                                ])
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse($report_contents as $report)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        <x-wui-checkbox id="selected" value="{{ $report->id }}" wire:model.live="selected"/>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                          {{ $report->reporter->fname .' '. $report->reporter->lname }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        @if($report->class)
                                            {{ $report->class->tutor->user->fname .' '. $report->class->tutor->user->lname }}
                                        @elseif($report->post)
                                            {{ $report->post->tutees->user->fname .' '. $report->post->tutees->user->lname }}
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        {{ Carbon::parse($report->date_reported)->format('F d, Y') }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        {{ $report->report_option }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        <div @class([
                                            'text-[#198754]' => $report->status === 'Approved',
                                            'text-[#871919]' => $report->status === 'Not Approved',
                                        ])>
                                            {{ $report->status }}
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-1 text-gray-700">
                                        <x-wui-button slate xs class="w-full" label="View Content"
                                        wire:click="openContentModal({{ $report->id }})"
                                        spinner="openContentModal({{ $report->id }})"/>
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
                    @if ($perPage > 5)
                        <div class="w-full sm:w-[7rem] mb-4 sm:mb-0">
                            <x-wui-select
                                placeholder='Per Page'
                                wire:model.live="perPage"
                                shadowless
                            >
                                <x-wui-select.option label="5" value="5" />
                                <x-wui-select.option label="10" value="10" />
                                <x-wui-select.option label="20" value="20" />
                            </x-wui-select.option>
                        </div>
                    @endif
                    {{ $report_contents->links() }}
                </div>
            </div>
        </div>
    </div>

    @if ($getContent)
    {{-- View Content Modal --}}
    <x-wui-modal.card wire:model="viewContentModal" align='center' max-width='xl'>
        <div class="space-y-2">
            <div class="flex gap-2 items-start">
                <div class="size-16">
                    <img
                        alt="Reporter Avatar"
                        src="{{ $getContent->reporter->avatar ? Storage::url($getContent->reporter->avatar) : asset('images/default.jpg') }}"
                        class="rounded-full object-cover border border-[#F1F5F9] overflow-hidden"
                    />
                </div>
                <div class="w-full space-y-1">
                    <p class="flex gap-2 font-semibold">
                        Reported by:
                        <span class="font-light">
                            {{ $getContent->reporter->fname .' '. $getContent->reporter->lname}}
                        </span>
                    </p>

                    {{-- reason --}}
                    <div class="font-semibold">
                        Reason:
                        <span class="font-light">
                            {{ $getContent->report_option}}
                        </span>
                    </div>
                </div>
            </div>

            {{-- feedback --}}
            @if ($getContent->comment)
                <div class="flex flex-col font-semibold">
                    <div class="flex gap-2 items-center">
                        Feedback
                    </div>
                    <span class="font-light">
                        {{ $getContent->comment }}
                    </span>
                </div>
            @endif

            {{-- collapsable class details --}}
            <div class="p-3 py-2 rounded-md bg-[#E1E7EC]" x-data="{ expanded: true }">
                <div class="flex cursor-pointer justify-between items-center" @click="expanded = ! expanded">
                    <span class="font-normal text-lg">Reported Content Details</span>
                    <template x-if='expanded == false' x-transition>
                        <x-wui-button xs label='View more' icon='arrow-down'
                            flat />
                    </template>
                    <template x-if='expanded == true' x-transition>
                        <x-wui-button xs label='View less' icon='arrow-up'
                            flat />
                    </template>
                </div>
                <div class="text-sm pt-3" x-show="expanded" x-collapse x-cloak>
                    @if($getContent->class_id)
                        <p>
                            <strong>Class ID: </strong> {{ $getContent->class_id }}
                        </p>
                        <p>
                            <strong>Class Name: </strong> {{ $getContent->class->class_name }}
                        </p>
                        <p>
                            <strong>Class by: </strong> {{ $getContent->class->tutor->user->fname .' '. $getContent->class->tutor->user->lname }}
                        </p>
                    @elseif ($getContent->post_id)
                        <p>
                            <strong>Post ID: </strong> {{ $getContent->post_id }}
                        </p>
                        <p>
                            <strong>Post Description: </strong> {{ $getContent->post->post_desc }}
                        </p>
                        <p>
                            <strong>Posted by: </strong> {{ $getContent->post->tutees->user->fname .' '. $getContent->post->tutees->user->lname}}
                        </p>
                    @endif

                    <p>
                        <strong>Report Status: </strong> {{ $getContent->status }}
                    </p>
                </div>
            </div>
        </div>

        <x-slot name='footer'>
            <div class="flex items-center justify-between font-light text-xs pt-2">
                <div class="flex gap-2 items-center text-[#64748B]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p>Reported on {{ $getContent->date_reported->format('l, F d Y g:i A') }}</p>
                </div>
            </div>
        </x-slot>
    </x-wui-modal.card>
    @endif
</section>

