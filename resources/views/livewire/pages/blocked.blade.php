<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use WireUi\Traits\Actions;
use Carbon\Carbon;
use App\Models\Blacklist;

new #[Layout('layouts.app')] class extends Component {
    use Actions;

    public $blockedUser;

    public function mount()
    {
        $this->blockedUser = Blacklist::where('reported_user_id', Auth::id())->first();
    }

    public function requestForUnblock()
    {
        $this->blockedUser->update(['request_status' => 'Pending']);

        $this->notification([
            'title'       => 'Requested!',
            'description' => 'Please be patient while we are reviewing your request.',
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);
    }

}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col justify-center items-center w-full">
            <h1 class="font-anton tracking-wide text-4xl text-gray-800 leading-tight">Access Denied</h1>
            <p class="font-semibold text-center">
                Your account will be back on:
                <span class="font-normal">
                    {{ Carbon::parse($blockedUser->blocked_at)->format('F d, Y, l h:i A') }}
                </span>
            </p>
            <img class="w-[40%] h-auto py-1" src="{{ asset('images/blacklist.svg') }}" alt="">
            <div class="md:-translate-y-8 gap-2 flex flex-col justify-center items-center">
                <p class="text-center text-lg text-gray-900">Your account has been blocked due to multiple reports.</p>
                @if ($blockedUser->report_count < 10)
                    <x-wui-button
                        wire:click="requestForUnblock"
                        spinner="requestForUnblock"
                        positive solid icon='refresh'
                        :disabled="$blockedUser->request_status == 'Pending'"
                        label="{{ $blockedUser->request_status == 'Pending' ? 'Requested' : 'Request for unblock'}}" />
                @else
                    <x-wui-badge md red icon="exclamation" label='You cannot request for unblocking at this moment.'/>
                @endif
            </div>
        </div>
    </div>

    <x-wui-notifications position="bottom-right" />
</section>
