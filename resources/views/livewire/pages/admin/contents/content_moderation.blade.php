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

    public function mount()
    {
        $this->availableOptions = ReportContent::distinct()
            ->pluck('report_option')
            ->toArray();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getDataQuery()->pluck('reporter_id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function reportContentStatus($value)
    {
        $report_contents = ReportContent::whereIn('reporter_id', $this->selected)->get();

        foreach ($report_contents as $report_content) {
            if(!($report_content->status === $value)) {
                $newStatus = ($report_content->status === 'Not Approved' || $report_content->status === 'Pending') ? 'Approved' : 'Not Approved';

                $report_content->status = $newStatus;
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
                                <x-wui-button label="Update Status" flat green sm icon='clipboard-check'/>
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
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse($report_contents as $report)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    <x-wui-checkbox id="selected" value="{{ $report->reporter_id }}" wire:model.live="selected"/>
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
</section>

