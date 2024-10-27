@php
    use Carbon\Carbon;
@endphp

<div class="pt-2">
    @if ($item['class_roster_details']->proof_of_payment)
        <div class="flex flex-col md:flex-row gap-2 md:items-center w-full">
            @if ($item['class_roster_details']->payment_status == 'Approved')
                <div class="outline-none inline-flex justify-center items-center group transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-1 text-xs px-2.5 py-1.5 ring-emerald-500 text-white bg-emerald-500 hover:bg-emerald-600 hover:ring-emerald-600"
                    disabled="disabled">
                    <svg class="w-3 h-3 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    Payment Approved
                </div>
            @elseif ($item['class_roster_details']->payment_status == 'Not Approved')
                <div class="outline-none inline-flex justify-center items-center group transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-1 text-xs px-2.5 py-1.5 ring-red-500 text-white bg-red-500 hover:bg-red-600 hover:ring-red-600"
                    disabled="disabled">
                    <svg class="w-3 h-3 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    Payment Denied
                </div>
                <x-wui-button wire:click="openPaymentModal({{ $item['class_roster_id'] }})"
                    spinner="openPaymentModal({{ $item['class_roster_id'] }})" dark xs icon='credit-card'
                    label='Send Proof of Payment' />
            @else
                <div class="outline-none inline-flex justify-center items-center group transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-1 text-xs px-2.5 py-1.5 ring-indigo-500 text-white bg-indigo-500 hover:bg-indigo-600 hover:ring-indigo-600"
                    disabled="disabled">
                    <svg class="w-3 h-3 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    Proof of Payment Sent
                </div>
            @endif
            <x-wui-button wire:click="viewPayment({{ $item['class_roster_id'] }})"
                spinner="viewPayment({{ $item['class_roster_id'] }})" neutral outline xs icon='credit-card'
                label='View Attached File' />
        </div>
    @else
        <x-wui-button wire:click="openPaymentModal({{ $item['class_roster_id'] }})"
            spinner="openPaymentModal({{ $item['class_roster_id'] }})" dark xs icon='credit-card'
            label='Send Proof of Payment' />
    @endif
</div>

@if (($date < Carbon::now()->format('Y-m-d') && $item['class_roster_details']->payment_status == 'Approved') && !$item['class_roster_details']->rated)
    <div class="flex flex-col md:flex-row md:items-center pt-1 w-full">
        <x-wui-button wire:click="reviewClassModal({{ $item['class_details']->id }})"
            spinner="reviewClassModal({{ $item['class_details']->id }})" info xs icon='star' label='Review Class' />
    </div>
{{-- @else
    <div
        class="outline-none inline-flex justify-center items-center group transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-1 text-xs px-2.5 py-1.5 ring-info-500 text-white bg-info-500 hover:bg-info-600 hover:ring-info-600">
        <svg class="w-3 h-3 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
            </path>
        </svg>
        Class Reviewed
    </div> --}}
@endif
