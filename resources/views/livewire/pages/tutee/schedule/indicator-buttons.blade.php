@php
    use Carbon\Carbon;
@endphp

<div class="pt-2">
    @if ($item['class_roster_details']->proof_of_payment)
        <div class="inline-flex gap-2 items-center">
            @if ($item['class_roster_details']->payment_status == 'Approved')
                <div class="outline-none inline-flex justify-center items-center group transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-1 text-xs px-2.5 py-1.5 ring-emerald-500 text-white bg-emerald-500 hover:bg-emerald-600 hover:ring-emerald-600" disabled="disabled">
                    <svg class="w-3 h-3 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Payment Approved
                </div>
            @elseif ($item['class_roster_details']->payment_status == 'Not Approved')
                <div class="outline-none inline-flex justify-center items-center group transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-1 text-xs px-2.5 py-1.5 ring-red-500 text-white bg-red-500 hover:bg-red-600 hover:ring-red-600" disabled="disabled">
                    <svg class="w-3 h-3 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Payment Denied
                </div>
                <x-wui-button wire:click="openPaymentModal({{ $item['class_roster_id'] }})"
                    spinner="openPaymentModal({{ $item['class_roster_id'] }})"
                    dark
                    xs
                    icon='credit-card'
                    label='Send Proof of Payment' />
            @else
                <div class="outline-none inline-flex justify-center items-center group transition-all ease-in duration-150 focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed rounded gap-x-1 text-xs px-2.5 py-1.5 ring-indigo-500 text-white bg-indigo-500 hover:bg-indigo-600 hover:ring-indigo-600" disabled="disabled">
                    <svg class="w-3 h-3 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Proof of Payment Sent
                </div>
            @endif
            <x-wui-button wire:click="viewPayment({{ $item['class_roster_id'] }})"
                        spinner="viewPayment({{ $item['class_roster_id'] }})"
                        neutral
                        outline
                        xs
                        icon='credit-card'
                        label='View Attached File' />
        </div>
    @else
        <x-wui-button wire:click="openPaymentModal({{ $item['class_roster_id'] }})"
                    spinner="openPaymentModal({{ $item['class_roster_id'] }})"
                    dark
                    xs
                    icon='credit-card'
                    label='Send Proof of Payment' />
    @endif
</div>

@if ($date < Carbon::now()->format('Y-m-d') && $item['class_roster_details']->payment_status == 'Approved')
    <x-wui-button wire:click="reviewClassModal({{ $item['class_details']->id }})"
                spinner="reviewClassModal({{ $item['class_details']->id }})"
                info
                xs
                icon='star'
                label='Review Class'
                />
@endif
