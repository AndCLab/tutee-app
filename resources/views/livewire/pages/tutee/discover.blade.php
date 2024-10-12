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

    public string $post_title ='';
    public $getFields = [];
    public $class_fields = [];
    public $class_date;
    public $class_fee = 0;
    public string $class_category = '';
    public string $class_type = '';
    public string $class_location = '';
    public $post_created;

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
            'post_title' => ['required', 'string', 'max:255'],
            'class_fields' => ['required'],
            'class_date' => ['required', 'date'],
            'class_fee' => ['required', 'numeric'],
            'class_category'=> ['required'],
            'class_type' => ['required'],
            'class_location' => ['string', 'max:255'],
        ]);
    }

    public function post()
    {
        $tutee = Tutee::where('user_id', Auth::id())->first();

        if (!$this->class_location) {
            $this->class_type = 'virtual';
        }elseif ($this->class_location) {
            $this->class_type = 'physical';
        } else {
            $this->notification([
                'title'       => 'Error',
                'description' => 'Either virtual or physical class',
                'icon'        => 'error',
                'timeout'     => 2500,
            ]);

            return;
        }

        $this->validation();

        Post::create([
            'tutee_id' => $tutee->id,
            'post_title' => $this->post_title,
            'class_fields' => json_encode($this->class_fields),
            'class_date' => $this->class_date,
            'class_fee' => $this->class_fee,
            'class_category' => $this->class_category,
            'class_type' => $this->class_type,
            'class_location' => $this->class_location,
            'post_created'=> 1
        ]);

        $this->reset(
            'post_title',
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
    }
}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-6">
        <div class="md:grid md:grid-row items-start gap-5 pb-3">
            <p class="capitalize font-semibold text-xl">Interests</p>
        </div>

        <div class="flex items-center space-x-3">
            <img
                alt="User Avatar"
                src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('images/default.jpg') }}"
                class="w-10 h-10 rounded-full object-cover border border-[#F1F5F9] overflow-hidden"
            />
            <div onclick="$openModal('postModal')" class="cursor-pointer border border-gray-300 bg-white rounded-md px-4 py-2 text-gray-700 hover:bg-gray-50 mx-4 max-w-xs">
                What do you want to learn?
            </div>
        </div>

        {{-- Post List --}}
        <div>
            <livewire:pages.tutee.post_components.post_list>
        </div>


        <!-- Post modal class -->
        <x-wui-modal name="postModal" align='center' max-width='xl' persistent>
            <x-wui-card>
                <h2 class="text-lg font-medium text-gray-900 flex space-x-4 mb-4">
                    Post Something
                </h2>

                <div class="flex items-center space-x-3 mb-4">
                    <img
                        alt="User Avatar"
                        src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('images/default.jpg') }}"
                        class="w-10 h-10 rounded-full object-cover border border-[#F1F5F9] overflow-hidden"
                    />
                    <strong class="block font-medium max-w-28 truncate">{{ Auth::user()->fname }}</strong>
                </div>

            <form>
                {{-- post title --}}
                <div class="mb-4">
                    <x-wui-input
                        wire:model="post_title"
                        placeholder="Enter post title"
                        shadowless />
                </div>

                <div class="flex space-x-4 mb-4">
                    {{-- class fields --}}
                        <x-wui-select
                            wire:model="class_fields"
                            placeholder="Select fields"
                            multiselect
                            shadowless
                        >
                            @foreach ($getFields as $field)
                                <x-wui-select.option
                                    label="{{ $field['field_name'] }}"
                                    value="{{ $field['field_name'] }}"
                                />
                            @endforeach
                        </x-wui-select>

                    {{-- class schedule --}}
                    <x-wui-datetime-picker
                            placeholder="Select Date"
                            wire:model.live="class_date"
                            parse-format="YYYY-MM-DD HH:mm"
                            display-format='dddd, MMMM D, YYYY'
                            :min="now()"
                            without-time
                            shadowless
                        />
                </div>

                <div class="flex space-x-4 mb-4">
                    {{-- Class Fee --}}
                    <x-wui-inputs.currency wire:model.live.debounce.250ms='class_fee' icon="cash" placeholder="Estimated Price" shadowless />

                    {{-- Class Category --}}
                    <x-wui-select
                        wire:model="class_category"
                        placeholder="Select Category"
                        shadowless
                    >
                    <x-wui-select.option label="Individual" value="individual" />
                    <x-wui-select.option label="Group" value="group" />
                    </x-wui-select>

                </div>

                Class Type
                {{-- Virtual or Physical Class --}}
                <div class="flex flex-col gap-4" x-data="{ tab: window.location.hash ? window.location.hash : '#virtual' }">
                    <ul class="flex bg-[#F1F5F9] px-1.5 py-1.5 gap-2 rounded-lg">
                        <li class="w-full text-center">
                            <a :class="tab !== '#virtual' ? '' : 'bg-white'"
                                class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out"
                                x-on:click.prevent="tab='#virtual'"> Virtual Class </a>
                        </li>
                        <li class="w-full text-center">
                            <a :class="tab !== '#physical' ? '' : 'bg-white'"
                                class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out"
                                x-on:click.prevent="tab='#physical'"> Physical Class </a>
                        </li>
                    </ul>

                    <div>
                        <div x-show="tab == '#physical'" x-cloak>
                            <div class="max-w-xl">
                                <x-wui-input wire:model='class_location' label="Class Venue" placeholder='Enter desired class venue' shadowless/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click='close'>
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-primary-button class="ms-3" wire:click="post" x-bind:disabled="!open">
                        {{ __('Post') }}
                    </x-primary-button>
                </div>
            </form>
            </x-wui-card>
        </x-wui-modal>
    </div>
    <x-wui-notifications position="bottom-right" />
</section>
