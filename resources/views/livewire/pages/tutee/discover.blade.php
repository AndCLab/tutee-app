<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use WireUi\Traits\Actions;
use App\Models\Fields;
use App\Models\Post;
use App\Models\Tutee;

new #[Layout('layouts.app')] class extends Component {
    use Actions;

    public string $post_desc ='';
    public $getFields = [];
    public $class_fields = [];
    public $class_date;
    public $class_fee = 0;
    public string $class_category = '';
    public string $class_type = 'virtual';
    public string $class_location = '';
    public $post_created;

    // states
    public $EstimatedPriceToggle;
    public $showPostModal;

    public function mount()
    {
        $this->getFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])
                                ->toArray();
    }

    public function validation()
    {
        $this->validate([
            'post_desc' => ['required', 'string', 'max:255'],
            'class_fields' => ['required'],
            'class_date' => ['required', 'date'],
            'class_fee' => ['required', 'numeric'],
            'class_category'=> ['required'],
            'class_type' => ['required'],
        ]);
    }

    public function post()
    {
        $tutee = Tutee::where('user_id', Auth::id())->first();

        if ($this->class_type === 'physical') {
            $rules['class_location'] = ['required', 'string', 'max:255'];
        }

        $this->validation();

        if ($this->class_type === 'virtual') {
            $this->class_location = '';
        }

        Post::create([
            'tutee_id' => $tutee->id,
            'post_desc' => $this->post_desc,
            'class_fields' => json_encode($this->class_fields),
            'class_date' => $this->class_date,
            'class_fee' => $this->class_fee,
            'class_category' => $this->class_category,
            'class_type' => $this->class_type,
            'class_location' => $this->class_type === 'physical' ? $this->class_location : '',
        ]);

        $this->reset(
            'post_desc',
            'class_fields',
            'class_date',
            'class_fee',
            'class_category',
            'class_type',
            'class_location'
        );

        $this->notification([
            'title'       => 'Success',
            'description' => 'Post created successfully.',
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);

        $this->dispatch('post-created');

        $this->showPostModal = false;
    }

    public function postModal()
    {
        $this->showPostModal = true;
    }

}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 py-6">
        <div class="lg:grid lg:grid-cols-3 items-start gap-5">
            <div class="lg:col-span-2 space-y-3">
                <p class="capitalize font-semibold text-xl mb-9">interests</p>

                <div class="flex items-center space-x-3 w-full pb-5">
                    <div class="size-10">
                        <img
                            alt="User Avatar"
                            src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('images/default.jpg') }}"
                            class="rounded-full object-cover border border-[#F1F5F9] overflow-hidden"
                        />
                    </div>
                    <div wire:click='postModal' class="cursor-pointer border text-[#64748B] border-gray-300 bg-white rounded-2xl w-full px-4 py-2 hover:bg-gray-50" wire:loading.class='opacity-50'>
                        What do you want to learn?
                    </div>
                </div>

                {{-- tabs for (discover and post list) --}}
                @include('livewire.pages.tutee.post_components.partials.tabs')
            </div>
            <div class="hidden lg:block space-y-3 sticky top-[5rem] overflow-y-auto max-h-[85vh] soft-scrollbar px-2 pb-3">
                <livewire:pages.tutee.components.top-tutors>
                <hr class="h-px my-2 bg-gray-200 border-0">
                <livewire:pages.tutee.components.upcoming_sched>
            </div>
        </div>

    </div>

    {{-- post modal --}}
    @include('livewire.pages.tutee.post_components.modals.post_modal')

    <x-wui-notifications position="bottom-right" />
</section>
