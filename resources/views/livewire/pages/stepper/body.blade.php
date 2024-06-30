<div class="max-w-6xl md:mx-auto mx-10">
    <div class="grid md:grid-flow-col md:grid-cols-12 grid-flow-row md:grid-rows-1 grid-rows-12 h-screen md:h-fit md:mx-10">
        <div class="mx-auto md:col-span-4 row-span-4 my-auto md:my-0">
            <div class="md:sticky md:top-0 md:flex md:h-screen md:justify-center gap-8 md:flex-col">
                @include('livewire.pages.stepper.header')
            </div>
        </div>
        <div class="w-full md:col-span-8 my-auto">
            @includeWhen($count === 1, 'livewire.pages.stepper.role')
            @if (session('error'))
                <div class="text-red-500">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-col justify-center items-center w-full pt-5">
                @if ($user_type === 'tutee')
                    @includeWhen($count === 2, 'livewire.pages.stepper.tutee.steps.form')
                    @includeWhen($count === 3, 'livewire.pages.stepper.tutee.steps.fields')
                @elseif ($user_type === 'tutor')
                    @includeWhen($count === 2, 'livewire.pages.stepper.tutor.steps.form')
                    @includeWhen($count === 3, 'livewire.pages.stepper.tutor.steps.fields')
                @endif
            </div>

            @includeWhen($count === 4, 'livewire.pages.stepper.confirm')

            <div class="flex justify-between md:w-3/4 mx-auto gap-3 mb-5">
                <x-secondary-button wire:click='prev_step' @class(['w-full', 'hidden' => $count === 1])>
                    Back
                </x-secondary-button>
                @if ($count < 4)
                    <x-primary-button wire:click='next_step' class="w-full">
                        Next
                    </x-primary-button>
                @endif
            </div>
        </div>
    </div>
</div>
