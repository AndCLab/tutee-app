<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use App\Models\Fields;
use WireUi\Traits\Actions;

new class extends Component {
    use Actions;

    public $interests = [];
    public $fields;
    public int $i;

    public $input = '';

    public function mount()
    {
        $this->fields = Fields::where('user_id', Auth::id())->get();
        $this->interests = $this->fields->toArray();
        $this->i = count($this->interests);
    }

    public function add_field()
    {
        $this->validate([
            'input' => 'required',
        ]);

        if (!in_array(['field_name' => $this->input], $this->interests)) {
            $this->interests[$this->i++] = ['field_name' => $this->input];

            // Add new field
            Fields::create([
                'user_id' => Auth::id(),
                'field_name' => $this->input,
            ]);

            $this->notification([
                'title' => 'Field added!',
                'description' => 'New field has been successfully added',
                'icon' => 'success',
                'timeout' => 3000,
            ]);
        }

        $this->reset('input');
    }

    public function remove_field($index)
    {
        // local variable getting that removed field value
        // this is not the field that has been declared publicly
        $field = $this->interests[$index];

        if (count($this->interests) > 3) {
            // Remove from the interests array
            unset($this->interests[$index]);
            $this->interests = array_values($this->interests);
            $this->i = count($this->interests);

            // find that field in Fields and simply remove it from the database
            Fields::where('user_id', Auth::id())
                ->where('field_name', $field['field_name'])
                ->delete();

            $this->notification([
                'title' => 'Field removed!',
                'icon' => 'success',
                'timeout' => 3000,
            ]);
        } else{
            $this->notification([
                'title' => 'You can\'t remove lesser than three fields',
                'icon' => 'error',
                'timeout' => 3000,
            ]);
        }

    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-semibold text-gray-900">
            {{ __('Add Fields') }}
        </h2>

        <p class="mt-1 text-xs text-gray-600">
            {{ __('Add any additional fields you want to explore or learn more about in detail.') }}
        </p>
    </header>

    <div class="flex flex-wrap gap-2 my-5">
        {{-- Interests fields --}}
        @if (!empty($interests))
            @foreach ($interests as $index => $item)
                <div wire:key='{{ $index }}'
                    class="bg-[#F1F5F9] text-[#0F172A] px-2 py-1 gap-2 text-sm rounded-3xl flex items-center">
                    <p>
                        {{ $item['field_name'] }}
                    </p>
                    <svg wire:click='remove_field({{ $index }})' xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 cursor-pointer">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
            @endforeach
        @else
            <div class="w-full flex flex-col justify-center items-center">
                <div>
                    <img class="w-auto h-40" src="{{ asset('images/empty_fields.jpg') }}" alt="">
                </div>
                <p class="text-sm text-gray-500">Create new fields!</p>
            </div>
        @endif
    </div>

    <form wire:submit='add_field' class="space-y-2 mt-2">
        <div class="w-full">
            <x-wui-input class="py-1.5" placeholder="Enter specific field" wire:model="input"
                wire:loading.attr='disabled' wire:loading.class='bg-gray-100 transition' autofocus autocomplete='off' />
        </div>
        <div class="grid">
            <x-secondary-button type='submit'>Add Field</x-secondary-button>
        </div>
    </form>
    <x-wui-notifications position="bottom-right" />

    <x-wui-notifications position="bottom-right" />
</section>
