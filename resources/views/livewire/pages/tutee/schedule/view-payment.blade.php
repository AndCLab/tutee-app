@if ($class_roster_payment)
    <x-wui-modal.card title="View Payment" align='start' max-width='md' blur wire:model="view_payment_modal">
        <div class="grid grid-row-1 sm:grid-row-2">
            <div class="w-full flex justify-center items-center">
                <img class="border-2 rounded-lg border-[#F1F5F9] overflow-hidden" src="{{ Storage::url($class_roster_payment->proof_of_payment) }}" alt="">
            </div>
        </div>

        <x-slot name="footer">
            <x-wui-button class="w-full" flat label="Cancel" x-on:click="close" />
        </x-slot>
    </x-wui-modal.card>
@endif
