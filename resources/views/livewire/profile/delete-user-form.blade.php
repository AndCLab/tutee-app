<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use App\Models\Fields;
use App\Models\Work;
use App\Models\Resume;
use App\Models\Certificate;
use App\Models\Institute;
use App\Models\Tutor;
use App\Models\Tutee;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        if (Auth::user()->user_type == 'tutor') {
            $tutor = Tutor::where('user_id', Auth::id())->first();
            if ($tutor) {
                Work::where('tutor_id', $tutor->id)->delete();
                Resume::where('tutor_id', $tutor->id)->delete();
                Certificate::where('tutor_id', $tutor->id)->delete();

                Tutor::where('user_id', Auth::id())->delete();
            }
        } else {
            $tutee = Tutee::where('user_id', Auth::id())->first();
            if ($tutee) {
                Institute::where('tutee_id', $tutee->id)->delete();

                Tutee::where('user_id', Auth::id())->delete();
            }
        }


        Fields::where('user_id', Auth::id())->delete();

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <x-danger-button
        class="w-full text-nowrap"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" maxWidth='md' :show="$errors->isNotEmpty()" focusable>
        <div class="">
            <form wire:submit="deleteUser" class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="mt-6">
                    <div>
                        <x-wui-inputs.password placeholder='Enter your current password' wire:model="password" label="Password"
                            autocomplete="password"/>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wireTarget='deleteUser'>
                        {{ __('Delete Account') }}
                    </x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>
</section>
