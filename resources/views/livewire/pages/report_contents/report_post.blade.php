@php
    use Carbon\Carbon;
@endphp

@if ($showReportPostModal)
    <x-wui-modal.card title="Report Content: Keep Our Community Safe and Respectful"
        wire:model="showReportPostModal" class="space-y-3" align='center' max-width='xl'>

        <div x-data="{
            radioGroupSelectedValue: $wire.entangle('selectedOption').live, // Bind with Livewire
            radioGroupOptions: [
                {
                    title: 'Inappropriate Content',
                    description: 'Contains offensive, harmful, or inappropriate material.',
                    value: 'inappropriate_content'
                },
                {
                    title: 'Spam or Advertising',
                    description: 'Post is promotional, spammy, or irrelevant.',
                    value: 'spam'
                },
                {
                    title: 'False Information',
                    description: 'The post contains misleading or incorrect information.',
                    value: 'false_information'
                },
                {
                    title: 'Copyright Infringement',
                    description: 'Post violates intellectual property or contains unauthorized use of copyrighted material.',
                    value: 'copyright_infringement'
                },
                {
                    title: 'Privacy Violation',
                    description: 'Exposes private or sensitive information without consent.',
                    value: 'privacy_violation'
                },
                {
                    title: 'Harassment or Bullying',
                    description: 'Post engages in targeted harassment or cyberbullying.',
                    value: 'harassment'
                },
                {
                    title: 'Malicious Links',
                    description: 'Contains harmful or phishing links that may compromise user security.',
                    value: 'malicious_links'
                }
            ]
        }" class="space-y-3 max-h-72 overflow-auto scroll-smooth soft-scrollbar pr-3">
            <template x-for="(option, index) in radioGroupOptions" :key="index">
                <label @click="radioGroupSelectedValue = option.title"
                    :class="{
                        'bg-gray-200 border-gray-300': radioGroupSelectedValue === option.title,
                    }"
                    class="flex items-start space-x-3 rounded-md border border-neutral-200/70 p-5 shadow-sm hover:bg-gray-100 cursor-pointer">
                    <input type="radio" :id="option.value" :name="option.value" :value="option.title"
                        class="hidden translate-y-px text-gray-900 focus:ring-gray-700" />
                    <span class="relative flex flex-col space-y-1.5 text-left leading-none">
                        <span x-text="option.title" class="font-semibold"></span>
                        <span x-text="option.description" class="text-sm opacity-50"></span>
                    </span>
                </label>
            </template>
        </div>

        {{-- comment --}}
        <div class="my-2">
            <x-wui-textarea autofocus label="Feedback" wire:model="comment" placeholder="Share us your feedback!" shadowless/>
        </div>

        <x-wui-errors />

        <x-slot name='footer'>
            <x-wui-button wire:click="submitPostReport" spinner="submitPostReport" class="w-full" label="Report" negative icon="exclamation"/>
        </x-slot>
    </x-wui-modal.card>
@endif
