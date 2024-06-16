<div class="mx-10">
    <div class="grid grid-rows-4 grid-flow-row md:grid-rows-1 md:grid-cols-8 md:grid-flow-col justify-center items-center h-screen max-w-5xl mx-auto">
        <div class="md:col-span-3">
            @include('livewire.pages.stepper.header')
        </div>
        <div class="md:col-span-5 row-span-4">
            @if ($count === 1)
                @include('livewire.pages.stepper.role')

                <div class="flex justify-between w-2/3 mx-auto gap-3">
                    <x-wui-button class="w-full" wire:click='next_step()' emerald label="Next" />
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
                            <x-wui-button class="w-full" wire:click='prev_step' neutral label="Back" />
                            @switch($count)
                                @case($count < 4)
                                    <x-wui-button class="w-full" wire:click='next_step' emerald label="Next" />
                                @break

                                @case($count === 4)
                                    <x-wui-button class="w-full" wire:click='submit' emerald label="Submit" />
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
                                    <x-wui-button class="w-full" wire:click='next_step' emerald label="Next" />
                                @break

                                @case($count === 4)
                                    <x-wui-button class="w-full" wire:click='submit' emerald label="Submit" />
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
