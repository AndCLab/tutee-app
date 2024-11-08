@php
    $confirmation = [
        'Platform Usage' => ['By accessing or using the TUTEE platform, you agree to comply with these terms and conditions and all applicable laws and regulations.', 'TUTEE reserves the right to modify or update these terms at any time without prior notice. It is your responsibility to review these terms periodically for any changes.'],
        'Account Registration' => ['To access certain features of the TUTEE platform, you may be required to create an account.', 'You agree to provide accurate, current, and complete information during the registration process and to update such information to keep it accurate, current, and complete.', 'You are solely responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.'],
        'Tutors and Tutees' => ['TUTEE provides a platform where individuals can connect for tutoring services. Tutors are independent contractors and are not employees or agents of TUTEE.', 'Tutees have access to a curated database of tutors and can select tutors based on their preferences and requirements.'],
        'Verification Process' => ['TUTEE offers a verification process for tutors to enhance their credibility. However, even tutors who are not verified can build trust through a rating system based on reviews from previous interactions with students.'],
        'Scheduling and Communication' => ['Tutees can schedule appointments with tutors directly through the TUTEE platform.', 'TUTEE facilitates seamless communication between tutors and tutees through integrated messaging features for ongoing support and clarification of doubts.'],
        'User Conduct' => ['You agree not to use the TUTEE platform for any unlawful or unauthorized purpose.', 'You agree not to interfere with or disrupt the integrity or performance of the TUTEE platform or its services.'],
        'Intellectual Property' => ['All content on the TUTEE platform, including but not limited to text, graphics, logos, images, and software, is the property of TUTEE or its licensors and is protected by intellectual property laws.'],
        'Limitation of Liability' => ['TUTEE shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or in connection with the use or inability to use the TUTEE platform.'],
        'Governing Law' => ['These terms and conditions shall be governed by and construed in accordance with the laws of the Republic of the Philippines, without regard to its conflict of law provisions.'],
        'Contact Information' => ['If you have any questions or concerns about these terms and conditions, please contact us at [tutee@email.com].'],
    ];
@endphp

<div class="mb-5 md:w-3/4 mx-auto">
    <h1 class="text-[#0C3B2E] text-center text-2xl md:text-3xl font-extrabold mb-10">User Profile Overview</h1>
    <div class="grid md:grid-cols-3 md:items-center text-[#0F172A] space-y-4 md:space-y-4">
        <div class="space-y-2 md:space-y-0 text-sm">
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
            <div class="flex flex-col md:items-center md:justify-center space-y-2 md:space-y-0">
                <p class="font-bold">Your Chosen Fields</p>
                <div class="flex w-full flex-wrap md:justify-center text-sm gap-1 pb-4">
                    @foreach ($selected as $index => $select)
                        <div class="bg-[#F1F5F9] text-[#0F172A] px-2 py-1 text-sm rounded-3xl flex items-center gap-2">
                            <p>
                                {{ $select }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

{{-- Application confirmation modal Class --}}
    <x-wui-modal name="applicationModal" align='center' max-width='xl' persistent>
        <x-wui-card>
            @if (Auth::user()->user_type == 'tutor')
                <h2 class="text-lg font-medium text-gray-900">
                    You are applying as Tutee.
                </h2>
            @else
                <h2 class="text-lg font-medium text-gray-900">
                    You are applying as Tutor.
                </h2>
            @endif
                <div>
                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click='close'>
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-primary-button class="ms-3" wire:click="submit" x-bind:disabled="!open">
                            {{ __('Continue') }}
                        </x-primary-button>
                    </div>
                </div>
        </x-wui-card>
    </x-wui-modal>

    <div class="my-2 text-pretty text-sm md:text-center">
        <p>By using this website, you agree to our <span class="underline cursor-pointer"
            onclick="$openModal('cardModal')">Terms and Condition</span></p>
    </div>

    {{-- Create class modal --}}
    <x-wui-modal name="cardModal" align='center' max-width='xl' persistent>
        <x-wui-card>
            <form wire:submit="submit" x-data="{ open: false }">
                <h2 class="text-lg font-medium text-gray-900">
                    TUTEE Platform - Terms and Conditions
                </h2>

                <div class="mt-2 text-sm text-gray-600 overflow-auto soft-scrollbar h-[390px] space-y-5 p-3">
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

                    <div class="mt-6">
                        <div class="block">

                            <div>
                                <label for="right-label" class="flex items-center ">
                                    <div class="relative flex items-start">

                                        <div class="flex items-center h-5">
                                            <input type="checkbox"
                                                class="form-checkbox rounded transition ease-in-out duration-100 border-secondary-300 focus:ring-primary-600 focus:border-primary-400 text-gray-600"
                                                @disabled(Auth::user()->apply_status == 'pending')
                                                @checked(Auth::user()->apply_status == 'pending')
                                                wire:model="confirm"
                                                x-model="open"
                                                id="right-label"
                                                name="confirm">
                                        </div>

                                        <div class="ml-2 text-sm">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400" for="right-label">
                                                I agree to the terms and condition
                                            </label>
                                        </div>
                                    </div>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>

                <div>
                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click='close'>
                            {{ __('Back') }}
                        </x-secondary-button>
                    @if (!(Auth::user()->apply_status == 'pending'))
                        <x-primary-button class="ms-3" x-bind:disabled="!open">
                            {{ __('Submit') }}
                        </x-primary-button>
                    </div>
                    @endif
                </div>
            </form>
        </x-wui-card>
    </x-wui-modal>
</div>
