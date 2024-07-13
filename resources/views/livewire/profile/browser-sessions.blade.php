<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Auth\StatefulGuard;
use WireUi\Traits\Actions;
use Jenssegers\Agent\Agent;

new class extends Component {
    use Actions;

    public function getSessionsProperty()
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))->where('user_id', Auth::user()->getAuthIdentifier())->orderBy('last_activity', 'desc')->get())->map(function ($session) {
            return (object) [
                'agent' => $this->createAgent($session),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    protected function createAgent($session)
    {
        return tap(new Agent(), fn($agent) => $agent->setUserAgent($session->user_agent));
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-semibold text-gray-900">
            {{ __('Log Sessions') }}
        </h2>

        <p class="mt-1 text-xs text-gray-600">
            {{ __('Manage and log out your active sessions on other browsers and devices.') }}
        </p>
    </header>

    @if (count($this->sessions) > 0)
        <div class="mt-5 space-y-6">
            <!-- Other Browser Sessions -->
            @foreach ($this->sessions as $session)
                <div class="flex items-center">
                    <div>
                        @if ($session->agent->isDesktop())
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-device-desktop w-8 h-8 text-gray-500">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M3 5a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1v-10z" />
                                <path d="M7 20h10" />
                                <path d="M9 16v4" />
                                <path d="M15 16v4" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-devices w-8 h-8 text-gray-500">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M13 9a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1v-10z" />
                                <path d="M18 8v-3a1 1 0 0 0 -1 -1h-13a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h9" />
                                <path d="M16 9h2" />
                            </svg>
                        @endif
                    </div>

                    <div class="ms-3">
                        <div class="text-sm text-gray-600">
                            {{ $session->agent->platform() ? $session->agent->platform() : __('Unknown') }} -
                            {{ $session->agent->browser() ? $session->agent->browser() : __('Unknown') }}
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">
                                {{ $session->ip_address }}:

                                @if ($session->is_current_device)
                                    <span class="text-green-500 font-semibold">{{ __('This device') }}</span>
                                @else
                                    {{ __('Last active') }} {{ $session->last_active }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- <x-wui-notifications position="bottom-right" /> --}}
</section>
