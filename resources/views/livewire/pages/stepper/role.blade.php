<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
// use App\Models\User;
// use App\Models\Tutee;
// use App\Models\Institute;
// use App\Models\Tutor;

new #[Layout('layouts.app')] class extends Component {
    public $user_type = '';



}; ?>

<div>

    <div class="w-2/3 mx-auto">
        <div class="mb-5">
            <input type="radio" id="tutee" name="user_type" value="tutee" class="hidden peer/tutee" wire:model.defer="user_type"/>
            <label for="tutee"
                class="inline-flex items-center justify-between w-full p-5 text-black bg-white border border-[#CBD5E1] rounded-lg cursor-pointer peer-checked/tutee:border-[#0C3B2E] peer-checked/tutee:text-[#0C3B2E] hover:text-gray-600 hover:bg-gray-100">
                <div class="block">
                    <div class="w-full text-lg font-semibold">I am a Tutee</div>
                </div>

            </label>
        </div>
        <div class="mb-5">
            <input type="radio" id="tutor" name="user_type" value="tutor" class="hidden peer/tutor" wire:model.defer="user_type"/>
            <label for="tutor"
                class="inline-flex items-center justify-between w-full p-5 text-black bg-white border border-[#CBD5E1] rounded-lg cursor-pointer peer-checked/tutor:border-[#0C3B2E] peer-checked/tutor:text-[#0C3B2E] hover:text-gray-600 hover:bg-gray-100">
                <div class="block">
                    <div class="w-full text-lg font-semibold">I am a Tutor</div>
                </div>
            </label>
        </div>
        @error('user_type')
            {{ $message }}
        @enderror
    </div>
</div>
