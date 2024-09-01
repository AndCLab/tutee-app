<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Classes;
use App\Models\ClassRoster;
use App\Models\Fields;

new #[Layout('layouts.app')] class extends Component {

    public $class;

    public function mount(int $id)
    {
        $this->class = ClassRoster::where('class_id', $id)->get();
    }

}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-6">
        <p>tutor view-students</p>
        @forelse ($class as $roster)
            @php
                $tutee = User::find($roster->tutee_id);
            @endphp
            {{ $tutee->fname }}
        @empty
            Empty
        @endforelse
    </div>
</section>
