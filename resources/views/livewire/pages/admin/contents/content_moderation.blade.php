<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
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

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-6">
        <p class="capitalize font-semibold text-xl">Content Moderation</p>
    </div>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">
        <div>
            {{-- search filter --}}
            <div class="sm:inline-flex sm:justify-between sm:items-center mb-4 w-full">
                {{-- Search content... --}}
                <div class="w-full sm:w-2/6">
                    <x-wui-input placeholder='Search a report content...' wire:model.live='search' shadowless/>
                </div>

                {{-- Report Option --}}
                <div>
                    <x-wui-select
                        wire:model.live="report_options" placeholder="Select Report Option/s" multiselect shadowless>
                        @foreach ($availableOptions as $option)
                            <x-wui-select.option label="{{ $option }}"
                                value="{{ $option }}" />
                        @endforeach
                    </x-wui-select>
                </div>

                @if ($selected || $selectAll)
                    <div class="mt-2 sm:mt-0">
                        <x-wui-dropdown class="w-full">
                            <x-slot name="trigger">
                                <x-wui-button label="Select Content" flat green sm icon='clipboard-check'/>
                            </x-slot>

                            <x-wui-dropdown.item label="Approved" wire:click="reportContentStatus('Approved')"/>
                            <x-wui-dropdown.item label="Not Approved" wire:click="reportContentStatus('Not Approved')"/>
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
                                'name' => 'reporter_id',
                                'displayName' => 'Reporter User ID'
                            ])

                            @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                'name' => 'fname',
                                'displayName' => 'First Name'
                            ])

                            @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                'name' => 'lname',
                                'displayName' => 'Last Name'
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
                                    {{ $report->reporter_id }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                      {{ $report->reporter->fname }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                      {{ $report->reporter->lname }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    {{ $report->date_reported }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    {{ $report->report_option }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    {{ $report->status }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-1 text-gray-700">
                                    <x-wui-button slate xs class="w-full" label="View Content"
                                    wire:click="openContentModal({{ $report->id }})"/>
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
        </div>
    </div>

    @if ($getContent)
    {{-- View Content Modal --}}
    <x-wui-modal.card wire:model="viewContentModal" class="space-y-3" align='center' max-width='xl'>
        <div class="flex gap-2 items-start">
            <div class="size-16">
                <img
                    alt="Reporter Avatar"
                    src="{{ $getContent->reporter->avatar ? Storage::url($getContent->reporter->avatar) : asset('images/default.jpg') }}"
                    class="rounded-full object-cover border border-[#F1F5F9] overflow-hidden"
                />
            </div>
            <div class="w-full space-y-2">
                <p class="flex gap-2 font-semibold">
                    {{ $getContent->reporter->fname .' '. $getContent->reporter->lname}}
                </p>
            </div>
        </div>

        {{-- class name --}}
        <div class="flex flex-col font-semibold">
            Reason
            <span class="font-light">
                {{ $getContent->report_option}}
            </span>
        </div>

        {{-- class description --}}
        <div class="flex flex-col font-semibold">
            <div class="flex gap-2 items-center">
                Feedback
            </div>
            <span class="font-light">
                {{ $getContent->comment}}
            </span>
        </div>

        {{-- collapsable class details --}}
        <div class="p-3 py-2 rounded-md bg-[#E1E7EC]" x-data="{ expanded: false }">
            <div class="flex cursor-pointer justify-between items-center" @click="expanded = ! expanded">
                <span class="font-semibold text-sm">Reported Content Details</span>
                <template x-if='expanded == false' x-transition>
                    <x-wui-button xs label='View more' icon='arrow-down'
                        flat />
                </template>
                <template x-if='expanded == true' x-transition>
                    <x-wui-button xs label='View less' icon='arrow-up'
                        flat />
                </template>
            </div>
            <div class="text-sm" x-show="expanded" x-collapse x-cloak>
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

