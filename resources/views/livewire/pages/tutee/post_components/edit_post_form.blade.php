<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use WireUi\Traits\Actions;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use App\Models\Fields;
use App\Models\Post;
use App\Models\Tutee;

new #[Layout('layouts.app')] class extends Component {
    use Actions;

    public $tutee;
    public $post;

    public string $post_desc ='';
    public $getFields = [];
    public $class_fields = [];
    public $class_date;
    public $class_fee = 0;
    public string $class_category = '';
    public string $class_type = '';
    public string $class_location = '';

    public function mount(int $id)
    {
        $this->post = Post::findOrFail($id);

        $this->getFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])
                                ->toArray();

        $this->post_desc = $this->post->post_desc;
        $this->class_fields = json_decode($this->post->class_fields, true);
        $this->class_date = Carbon::parse($this->post->class_date);
        $this->class_fee = $this->post->class_fee;
        $this->class_category = $this->post->class_category;
        $this->class_type = $this->post->class_type;

        if ($this->post->class_type == 'physical') {
            $this->class_location = $this->post->class_location;
        }
    }

    public function editPost()
    {
        $rules = [
            'post_desc' => ['required', 'string', 'max:255'],
            'class_fields' => ['required'],
            'class_date' => ['required', 'date'],
            'class_fee' => ['required', 'numeric'],
            'class_category'=> ['required'],
            'class_type' => ['required'],
        ];


        if ($this->class_type === 'physical') {
            $rules['class_location'] = ['required', 'string', 'max:255'];
        }

        $this->validate($rules);

        if ($this->class_type === 'virtual') {
            $this->class_location = '';
        }

        $this->post->post_desc = $this->post_desc;
        $this->post->class_fields = is_array($this->class_fields) ? json_encode($this->class_fields) : $this->class_fields;
        $this->post->class_date = $this->class_date;
        $this->post->class_fee = $this->class_fee;
        $this->post->class_category = $this->class_category;
        $this->post->class_type = $this->class_type;

        if ($this->class_type === 'physical') {
            $this->post->class_location = $this->class_location;
        } else {
            $this->post->class_location = '';
        }

        $this->post->save();

        $this->notification([
            'title'       => 'Success',
            'description' => 'Post edited successfully.',
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);
    }
}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-3xl mx-auto px-2 sm:px-6 lg:px-8 py-6">
        <form wire:submit='editPost'>
            <div class="inline-flex mb-4 justify-end items-center w-full">
                <div class="inline-flex gap-2 items-center">
                    <x-primary-button class="text-xs" type='submit' wireTarget='editPost'>
                        Edit Post
                    </x-primary-button>
                </div>
            </div>

                {{-- post desc --}}
                <div class="mb-4">
                    <x-wui-textarea label='Post Description' autofocus wire:model="post_desc" placeholder="Enter post description" shadowless/>
                </div>

                <div class="flex space-x-3 mb-4">
                    {{-- class fields --}}
                        <x-wui-select
                            wire:model="class_fields"
                            label="Class Fields"
                            placeholder="Edit fields"
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
                            placeholder="Edit Date"
                            wire:model="class_date"
                            label="Class Date"
                            parse-format="YYYY-MM-DD HH:mm"
                            display-format='dddd, MMMM D, YYYY'
                            :min="now()"
                            without-time
                            shadowless
                        />
                </div>

                <div class="flex space-x-3 mb-4">
                    {{-- Class Fee --}}
                    <x-wui-inputs.currency
                        wire:model.live.debounce.250ms='class_fee'
                        icon="cash"
                        label="Class Fee"
                        placeholder="Estimated Price"
                        shadowless />

                    {{-- Class Category --}}
                    <x-wui-select
                        wire:model="class_category"
                        label="Class Category"
                        placeholder="Edit Category"
                        shadowless
                    >
                    <x-wui-select.option label="Individual" value="individual" />
                    <x-wui-select.option label="Group" value="group" />
                    </x-wui-select>

                </div>

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-400 mr-2">
                    Class Type
                </label>
                {{-- Virtual or Physical Class --}}
                <div class="flex flex-col gap-4" x-data="{ classType: '{{ $class_type }}' }">
                    {{-- Radio buttons with original tab styling --}}
                    <ul class="flex bg-[#F1F5F9] px-1.5 py-1.5 gap-2 rounded-lg">
                        <li class="w-full text-center">
                            <label :class="classType !== 'virtual' ? '' : 'bg-white'"
                                class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out">
                                <input type="radio" wire:model.defer='class_type' id="virtual" name="virtual" x-model="classType" value="virtual" class="hidden" />
                                Virtual Class
                            </label>
                        </li>
                        <li class="w-full text-center">
                            <label :class="classType !== 'physical' ? '' : 'bg-white'"
                                class="inline-flex w-full cursor-pointer justify-center gap-3 rounded-md px-2 py-1.5 text-sm font-semibold transition-all ease-in-out">
                                <input type="radio" wire:model.defer='class_type' id="physical" name="physical" x-model="classType" value="physical" class="hidden" />
                                Physical Class
                            </label>
                        </li>
                    </ul>

                    {{-- Conditional inputs --}}
                    <div>
                        <div x-show="classType === 'physical'" x-cloak>
                            <div class="w-full">
                                <x-wui-input
                                    wire:model='class_location'
                                    label="Class Venue"
                                    placeholder='Enter class venue'
                                    shadowless
                                    x-init="$watch('classType', value => {
                                        if (value === 'virtual') {
                                            $wire.set('class_location', null);
                                        }
                                    })"/>
                            </div>
                        </div>
                    </div>
                </div>

        </form>
    </div>
    <x-wui-notifications position="bottom-right" />
</section>
