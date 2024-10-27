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
            $this->selected = $this->getDataQuery()->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updateRequestStatus($value)
    {
        $blacklists = Blacklist::whereIn('id', $this->selected)->get();

        foreach ($blacklists as $blacklist) {
            if(!($blacklist->request_status === $value) && $blacklist->request_status != null) {
                $blacklist->request_status = $value;
                if ($value === 'Approved') {
                    $blacklist->blocked_at = null;
                }
                $blacklist->save();
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
            'blacklists' => $this->getDataQuery(),
        ];
    }

    public function getDataQuery()
    {
        return Blacklist::query()
            ->select('blacklists.*')
            ->join('users', 'blacklists.reported_user_id', '=', 'users.id')
            ->wherenull('blacklists.blocked_at')
            ->where('blacklists.report_count', '<', 3)
            ->where(function ($query) {
                $query->where('blacklists.request_status', 'pending')
                    ->orwhere('blacklists.request_status', 'not approved')
                    ->orwherenull('blacklists.request_status');
            })
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

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">
            <div>
                {{-- search filter --}}
                <div class="md:grid md:grid-row items-start gap-5">
                    <p class="capitalize font-semibold text-xl">User Management</p>

                    <div class="sm:inline-flex sm:justify-between sm:items-center mb-4 w-full">
                        {{-- Search blacklisted... --}}
                        <div class="w-full sm:w-2/6">
                            <x-wui-input placeholder='Search a blacklisted user...' wire:model.live='search' shadowless/>
                        </div>

                        @if ($selected || $selectAll)
                            <div class="mt-2 sm:mt-0">
                                <x-wui-dropdown class="w-full">
                                    <x-slot name="trigger">
                                        <x-wui-button label="Update Request Status" flat green sm icon='clipboard-check'/>
                                    </x-slot>

                                    <x-wui-dropdown.item label="Approved" wire:click="updateRequestStatus('Approved')"/>
                                    <x-wui-dropdown.item label="Not Approved" wire:click="updateRequestStatus('Not Approved')"/>
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
                                    'name' => 'reported_user_id',
                                    'displayName' => 'Blacklisted User ID'
                                ])

                                @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                    'name' => 'lname',
                                    'displayName' => 'Blacklisted Name'
                                ])

                                @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                    'name' => 'email',
                                    'displayName' => 'Email Address'
                                ])

                                @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                    'name' => 'report_count',
                                    'displayName' => 'Report Count'
                                ])

                                @include('livewire.pages.tutor.schedule.includes.sort-icons-table', [
                                    'name' => 'request_status',
                                    'displayName' => 'Unblock Request Status'
                                ])
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse($blacklists as $blacklist)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        @if($blacklist->request_status != null)
                                            <x-wui-checkbox id="selected" value="{{ $blacklist->id }}" wire:model.live="selected"/>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        {{ $blacklist->reported_user_id }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        {{ $blacklist->reportedUser->lname .', '. $blacklist->reportedUser->fname }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        {{ $blacklist->reportedUser->email }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        {{ $blacklist->report_count }} Report/s
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        @if ($blacklist->request_status)
                                            <div @class([
                                                'text-[#198754]' => $blacklist->request_status === 'Approved',
                                                'text-[#871919]' => $blacklist->request_status === 'Not Approved',
                                            ])>
                                                {{ $blacklist->request_status }}
                                            </div>
                                        @else
                                            <span class="italic font-normal text-xs">It hasn't been requested yet.</span>
                                        @endif
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
                    {{ $blacklists->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

