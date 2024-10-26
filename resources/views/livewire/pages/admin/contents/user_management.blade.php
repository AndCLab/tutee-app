<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Blacklist;

new #[Layout('layouts.admin')] class extends Component {
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $selected = [];
    public $selectAll = false;
    public $perPage = 5;

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getDataQuery()->pluck('reported_user_id')->toArray();
        } else {
            $this->selected = [];
        }
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
            'blacklists' => $this->getDataQuery(),
        ];
    }

    public function getDataQuery()
    {
        return Blacklist::query()
            ->select('blacklists.*')
            ->join('users', 'blacklists.reported_user_id', '=', 'users.id')
            ->search($this->search)
            ->when($this->sortField, function ($query) {
                $query->orderBy($this->sortField, $this->sortDirection);
            })
            ->paginate($this->perPage);

    }
}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-6">
        <p class="capitalize font-semibold text-xl">User Management</p>
    </div>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">
        <div>
            {{-- search filter --}}
            <div class="sm:inline-flex sm:justify-between sm:items-center mb-4 w-full">
                {{-- Search blacklisted... --}}
                <div class="w-full sm:w-2/6">
                    <x-wui-input placeholder='Search a blacklisted user...' wire:model.live='search' shadowless/>
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
                                'name' => 'reported_user_id',
                                'displayName' => 'Blacklisted User ID'
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
                                'name' => 'email',
                                'displayName' => 'Email Address'
                            ])

                            @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                'name' => 'report_count',
                                'displayName' => 'Report Count'
                            ])
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse($blacklists as $blacklist)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    <x-wui-checkbox id="selected" value="{{ $blacklist->reported_user_id }}" wire:model.live="selected"/>
                                </td>

                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    {{ $blacklist->reported_user_id }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    {{ $blacklist->reportedUser->fname }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    {{ $blacklist->reportedUser->lname }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    {{ $blacklist->reportedUser->email }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    {{ $blacklist->report_count }}
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

