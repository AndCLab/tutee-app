<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use WireUi\Traits\Actions;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\Fields;
use App\Models\Post;
use App\Models\Tutee;

new #[Layout('layouts.app')] class extends Component {
    use Actions;

    public $tutee;

    public string $post_title ='';
    public $getFields = [];
    public $class_fields = [];
    public $class_date;
    public $class_fee = 0;
    public string $class_category = '';
    public string $class_type = '';
    public string $class_location = '';

    public $posts;

    #[Url(as: 'sort_by')]
    public $sort_by;

    #[Url(as: 'post_created')]
    public $post_created;

    public bool $isEmptyPost = false;

    public $showDeletePostModal;
    public $deletePostId;

    public function mount()
    {
        $this->sort_by = 'desc';
        $this->post_created = '1';
        $this->tutee = Tutee::where('user_id', Auth::id())->first();

        $this->posts = Post::where('tutee_id', $this->tutee->id)
                            ->where('post_created', 1)
                            ->orderBy('created_at', $this->sort_by)
                            ->get();

        $this->getFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])
                                ->toArray();

        $this->isEmptyPost = $this->posts->isEmpty();
    }

    public function updateList($isNotEmpty)
    {
        if ($this->isEmptyPost != $isNotEmpty) {
            $this->isEmptyPost = $isNotEmpty;
        }

        $this->posts = Post::where('tutee_id', $this->tutee->id)
                            ->where('post_created', $this->post_created)
                            ->orderBy('created_at', $this->sort_by)
                            ->get();
    }

    public function deletePost()
    {
        $post = Post::find($this->deletePostId);

        if ($post) {

            $post->delete();

            $this->notification([
                'title' => 'Removed',
                'description' => 'Successfully removed post',
                'icon' => 'success',
                'timeout' => 2500,
            ]);

            $this->showDeletePostModal = false;

            $this->mount();
        } else {
            $this->notification([
                'title' => 'Error',
                'description' => 'Post not found',
                'icon' => 'error',
                'timeout' => 2500,
            ]);
        }
    }

    public function deletePostModal($postId)
    {
        $this->showDeletePostModal = true;
        $this->deletePostId = $postId;
    }
}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    {{--<p class="capitalize font-semibold text-xl mb-9">post list</p> --}}

    {{-- Post List: Post Cards --}}
    <div class="space-y-3">
        @forelse ($posts as $post)
            <div class="w-full bg-[#F1F5F9] p-4 pb-2 rounded-md text-[#0F172A] space-y-4" wire:loading.remove>
                <div class="space-y-1">
                    <div class="flex justify-between items-center">
                        <div class="inline-flex items-center gap-2">
                            <p class="font-semibold">{{ $post->post_title }} - Created by: {{ $post->tutee_id}}</p>
                            @if ($post->class_category == 'group')
                                <x-wui-badge flat warning label="{{ $post->class_category }}" />
                            @else
                                <x-wui-badge flat purple label="{{ $post->class_category }}" />
                            @endif
                        </div>
                        @if ($post->post_created == 1 && $post->tutee_id == $tutee->id)
                            <x-wui-dropdown>
                                <x-wui-dropdown.header class="font-semibold" label="Actions">
                                    <x-wui-dropdown.item wire:navigate href="{{ route('edit-post', $post->id) }}"
                                        icon='pencil-alt' label="Edit" />
                                    <x-wui-dropdown.item wire:click='deletePostModal({{ $post->id }})'
                                        icon='trash' label="Delete" />
                                </x-wui-dropdown.header>
                            </x-wui-dropdown>
                        @endif
                    </div>
                    <div class="flex gap-2 items-center text-[#64748B] text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p>Post at {{ $post->created_at->format('l jS \\of F Y h:i A') }}</p>
                    </div>
                </div>

                <div x-data="{ expanded: false }">
                    <div class="text-sm p-3 rounded-md bg-[#E1E7EC]" x-show="expanded" x-collapse x-cloak>
                        <p>
                            <strong>Class Field/s:</strong>
                            {{ implode(', ', array_filter(json_decode($post->class_fields, true))) }}
                        </p>
                        <p>
                            <strong>Desired Date:</strong> {{ $post->class_date }}
                        </p>
                        <p>
                            <strong>Estimated Fee:</strong> {{ $post->class_fee == 0.0 ? 'Free Class' : number_format($post->class_fee, 2) }}
                        </p>
                        <p>
                            <strong>Class Type:</strong> {{ ucfirst($post->class_type) }}
                        </p>
                        <p>
                            <strong>Class Location:</strong> {{ $post->class_location }}
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <template x-if='expanded == false' x-transition>
                            <x-wui-button @click="expanded = ! expanded" xs label='View more' icon='arrow-down'
                                flat />
                        </template>
                        <template x-if='expanded == true' x-transition>
                            <x-wui-button @click="expanded = ! expanded" xs label='View less' icon='arrow-up'
                                flat />
                        </template>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col gap-3 justify-center items-center w-full" wire:loading.remove>
                <img class="size-60" src="{{ asset('images/empty_class.svg') }}" alt="">
                <p class="font-semibold text-xl">No Posts</p>
            </div>
        @endforelse
    </div>

    {{-- Delete Post Modal --}}
    <x-wui-modal wire:model="showDeletePostModal" persistent align='center' max-width='sm'>
        <x-wui-card title="Delete Post">
            <p class="text-gray-600">
                Are you sure you want to remove this post?
            </p>
            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-wui-button flat label="Cancel" x-on:click="close" />
                    <x-wui-button wire:click='deletePost' spinner='deletePost' negative label="Yes, Delete it" />
                </div>
            </x-slot>
        </x-wui-card>
    </x-wui-modal>
</section>
