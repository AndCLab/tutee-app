<x-wui-modal.card title="Leave Class" align='center' max-width='sm' blur wire:model="leave_class_modal">
    <div class="grid grid-row-1 sm:grid-row-2">
        <div class="w-full flex justify-center items-center">
            Are you sure you want to leave?
        </div>
    </div>

    <x-slot name="footer">
        <x-wui-button class="w-full" negative label="Leave Class" wire:click="leaveClass" spinner='leaveClass' />
    </x-slot>
</x-wui-modal.card>
