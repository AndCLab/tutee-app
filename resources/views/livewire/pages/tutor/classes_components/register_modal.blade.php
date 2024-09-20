{{-- class registration modal --}}
<x-wui-modal wire:model="showRegistrationDate" max-width='md' persistent>
    <x-wui-card title='Class Registration Date'>
        <div class="grid grid-cols-1 gap-4">
            <x-wui-datetime-picker
                label="Start Date Time"
                placeholder="January 1, 2000"
                wire:model.blur="regi_start_date"
                parse-format="YYYY-MM-DD HH:mm"
                display-format='dddd, MMMM D, YYYY h:mm A'
                :min="now()"
                interval="30"
                min-time="08:00"
                max-time="21:40"
                shadowless
            />
            <x-wui-datetime-picker
                label="End Date Time"
                placeholder="December 1, 2000"
                wire:model.blur="regi_end_date"
                parse-format="YYYY-MM-DD HH:mm"
                display-format='dddd, MMMM D, YYYY h:mm A'
                :min="now()"
                interval="30"
                min-time="08:00"
                max-time="21:40"
                shadowless
            />
        </div>
        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">
                <x-wui-button primary label="Done" spinner='showClassSchedule' x-on:click='close' />
            </div>
        </x-slot>
    </x-wui-card>
</x-wui-modal>
