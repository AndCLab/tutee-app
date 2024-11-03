<x-wui-modal.card title="Send Proof of Payment" align='start' max-width='md' wire:model="payment_modal">
    <div class="grid grid-row-1 sm:grid-row-2">
        <div class="w-full">
            <label for="payment" class="flex w-full cursor-pointer flex-col items-center justify-center rounded-md border border-dashed border-gray-300 bg-gray-50 py-5">
                <div class="mb-3 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <g id="Upload 02">
                    <path id="icon" d="M16.296 25.3935L19.9997 21.6667L23.7034 25.3935M19.9997 35V21.759M10.7404 27.3611H9.855C6.253 27.3611 3.33301 24.4411 3.33301 20.8391C3.33301 17.2371 6.253 14.3171 9.855 14.3171V14.3171C10.344 14.3171 10.736 13.9195 10.7816 13.4326C11.2243 8.70174 15.1824 5 19.9997 5C25.1134 5 29.2589 9.1714 29.2589 14.3171H30.1444C33.7463 14.3171 36.6663 17.2371 36.6663 20.8391C36.6663 24.4411 33.7463 27.3611 30.1444 27.3611H29.2589" stroke="#4F46E5" stroke-width="1.6" stroke-linecap="round" />
                    </g>
                </svg>
                </div>
                <h2 class="mb-1 text-center text-xs font-normal leading-4 text-gray-400">PNG, JPG or PDF, smaller than 2MB</h2>
                <h4 class="text-center text-sm font-medium leading-snug text-gray-900">Upload your Proof of Payment here</h4>
                <input id="payment" wire:model.live="payment" type="file" accept=".png,.jpg,.jpeg" class="hidden" />
            </label>
        </div>

        <div class="w-full flex justify-center items-center">
            {{-- spinner --}}
            <div role="status" class="mt-4" wire:loading wire:target='payment'>
                <svg class="animate-spin size-6 shrink-0"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            @if ($payment)
                <img class="mt-4 border-2 rounded-lg border-[#F1F5F9] overflow-hidden" src="{{ $payment->temporaryUrl() }}">
            @endif
        </div>
    </div>

    <x-slot name="footer">
        <div class="grid grid-cols-2 gap-2 items-center">
            <x-wui-button class="w-full" flat label="Close" x-on:click="close" />
            <x-wui-button class="w-full" :disabled="!$payment" primary label="Upload Payment" wire:click="sendPayment" spinner='sendPayment' />
        </div>
    </x-slot>
</x-wui-modal.card>
