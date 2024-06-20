<div class="mx-10">
    <div class="grid grid-rows-4 grid-flow-row md:grid-rows-1 md:grid-cols-8 md:grid-flow-col justify-center items-center h-screen max-w-5xl mx-auto">
        <div class="md:col-span-3">
            @include('livewire.pages.stepper.header')
        </div>
        <div class="md:col-span-5 row-span-4">
            @if ($user_type === 'tutee')
                @includeWhen($count === 2, 'livewire.pages.stepper.tutee.steps.form')
                @includeWhen($count === 3, 'livewire.pages.stepper.tutee.steps.fields')
            @elseif ($user_type === 'tutor')
                @includeWhen($count === 2, 'livewire.pages.stepper.tutor.steps.form')
                @includeWhen($count === 3, 'livewire.pages.stepper.tutor.steps.fields')
            @endif

            @includeWhen($count === 4, 'livewire.pages.stepper.confirm')

            <div class="flex justify-between w-3/4 mx-auto gap-3">
                <x-secondary-button wire:click.prevent='prev_step' class="w-full @if ($count === 1)
                    hidden
                @endif">
                    Back
                </x-secondary-button>
                @if ($count < 4)
                    <x-primary-button wire:click.prevent='next_step' class="w-full">
                        Next
                    </x-primary-button>
                @endif
            </div>
        </div>
    </div>
</div>


