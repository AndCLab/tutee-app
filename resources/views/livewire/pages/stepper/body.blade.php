<div class="mx-10">
    <div class="grid grid-rows-4 grid-flow-row md:grid-rows-1 md:grid-cols-8 md:grid-flow-col justify-center items-center h-screen max-w-5xl mx-auto">
        <div class="md:col-span-3">
            @include('livewire.pages.stepper.header')
        </div>
        <div class="md:col-span-5 row-span-4">
            @if ($count === 1)
                @include('livewire.pages.stepper.role')

                <div class="flex justify-between w-2/3 mx-auto gap-3">
                    <x-primary-button wire:click='next_step' class="w-full">
                        Next
                    </x-primary-button>
                </div>
            @else
                @switch($user_type)
                    @case('tutee')
                        @if (session('error'))
                            {{ session('error') }}
                        @endif

                        @if ($count === 2)
                            @include('livewire.pages.stepper.tutee.steps.form')
                        @endif

                        @if ($count === 3)
                            @include('livewire.pages.stepper.tutee.steps.fields')
                        @endif

                        <div class="flex justify-between w-2/3 mx-auto gap-3">
                            <x-secondary-button wire:click='prev_step' class="w-full">
                                Back
                            </x-secondary-button>
                            @switch($count)
                                @case($count < 4)
                                    <x-primary-button wire:click='next_step' class="w-full">
                                        Next
                                    </x-primary-button>
                                @break

                                @case($count === 4)
                                    <x-primary-button wire:click='submit' class="w-full">
                                        Submit
                                    </x-primary-button>
                                @break

                                @default
                            @endswitch
                        </div>
                    @break

                    @case('tutor')
                        @if (session('error'))
                            {{ session('error') }}
                        @endif

                        @if ($count === 2)
                            @include('livewire.pages.stepper.tutor.steps.form')
                        @endif

                        @if ($count === 3)
                            Fields for tutor
                        @endif

                        <div class="flex justify-between w-2/3 mx-auto gap-3">
                            <x-wui-button class="w-full" wire:click='prev_step' neutral label="Back" />
                            @switch($count)
                                @case($count < 4)
                                    <x-primary-button wire:click='next_step' class="w-full">
                                        Next
                                    </x-primary-button>
                                @break

                                @case($count === 4)
                                    <x-primary-button wire:click='submit' class="w-full">
                                        Submit
                                    </x-primary-button>
                                @break

                                @default
                            @endswitch
                        </div>
                    @break

                    @default
                @endswitch
            @endif
        </div>

    </div>
</div>
