<div class="my-5 w-3/4 mx-auto">
    <h1 class="text-[#0C3B2E] text-center text-3xl font-extrabold">User Profile Overview</h1>
    <div class="grid grid-cols-3 text-[#0F172A]">
        <div class="space-y-2">
            <div class="flex flex-col">
                <p class="font-bold">Full Name</p>
                <p>{{ Auth::user()->fname . ' ' . Auth::user()->lname }}</p>
            </div>
            <div class="flex flex-col">
                <p class="font-bold">Email Address</p>
                <p>{{ Auth::user()->email }}</p>
            </div>
        </div>
        <div class="col-span-2">
            <div class="flex flex-col items-center justify-center">
                <p class="font-bold">Your Chosen Fields</p>
                <div class="flex w-full flex-wrap justify-center gap-2 pb-4">
                    @foreach ($selected as $index => $select)
                        <div class="bg-[#F1F5F9] text-[#0F172A] px-3 py-2 text-sm rounded-3xl flex items-center gap-2">
                            <p>
                                {{ $select }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mt-2.5 text-pretty text-sm text-center">
        <p>By using this website, you agree to our <span class="underline cursor-pointer" x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'terms-and-conditions')">Terms and Condition</span></p>
    </div>

    <x-modal name="terms-and-conditions" focusable>
        <form wire:submit="submit" class="p-6">

            <h2 class="text-lg font-medium text-gray-900">
                TUTEE Platform - Terms and Conditions
            </h2>

            <div class="mt-1 text-sm text-gray-600 overflow-auto soft-scrollbar h-[400px] space-y-5">
                <p>
                    Welcome to TUTEE, the revolutionary platform designed to enhance the tutoring experience for both
                    tutees
                    and tutors. Before you begin using our services, please carefully review the following terms and
                    conditions:
                </p>

                <ol class="list-outside list-decimal p-4 pl-8">
                    @foreach ($confirmation as $item => $confirm)
                        <div class="pb-5">
                            <li>{{ $item }}</li>
                            @foreach ($confirm as $content)
                                <div class="pl-10">
                                    <ul class="list-outside list-disc">
                                        <li>{{ $content }}</li>
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </ol>

                By using the TUTEE platform, you acknowledge that you have read, understood, and agree to be bound by
                these terms and conditions. If you do not agree to these terms, you may not access or use the TUTEE
                platform.

            </div>

            <div x-data="{ open: false }">
                <div class="mt-6">
                    <div class="block">
                        <label for="confirm" class="inline-flex items-center">
                            <input id="confirm" type="checkbox" x-model="open"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">I agree to the terms and condition</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-primary-button class="ms-3" x-bind:disabled="!open">
                        {{ __('Submit') }}
                    </x-primary-button>
                </div>
            </div>


        </form>
    </x-modal>
</div>
