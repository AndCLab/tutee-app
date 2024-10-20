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

    #[Url(as: 'sort_by')]
    public $sort_by = 'desc';

    public $getPost;

    // states
    public $showDeletePostModal;
    public $showTuteePost;
    public $deletePostId;
    public $pages = 5;

    public function deletePost()
    {
        $post = Post::findOrFail($this->deletePostId);

        if ($post) {

            $post->delete();

            $this->notification([
                'title' => 'Removed',
                'description' => 'Successfully removed post',
                'icon' => 'success',
                'timeout' => 2500,
            ]);

            $this->showDeletePostModal = false;
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

    public function viewPostModal($postId)
    {
        $this->showTuteePost = true;
        $this->getPost = Post::findOrFail($postId);
    }

    public function loadMore()
    {
        $this->pages += 5;
    }

    #[On('post-created')]
    public function with(): array
    {
        $getFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])
                                ->toArray();

        $tutee_id = Tutee::where('user_id', Auth::id())->pluck('id')->first();

        $posts = Post::whereHas('tutees', function ($query) use ($tutee_id, $getFields) {

                        // if tutee account exists then will only display its posts
                        if ($tutee_id) {
                            $query->where('tutee_id', $tutee_id)
                                ->whereHas('user.fields', function ($subQuery) use ($getFields) {
                                    $subQuery->whereIn('field_name', $getFields);
                                });
                        } else {

                            // otherwise, will display of all the posts (tutor side)
                            $query->whereNotNull('tutee_id')
                                ->whereHas('user.fields', function ($subQuery) use ($getFields) {
                                    $subQuery->whereIn('field_name', $getFields);
                                });
                        }
                    })
                    ->orderBy('created_at', $this->sort_by)
                    ->take($this->pages)
                    ->get();

        return [
            'posts' => $posts,
        ];
    }

}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    {{-- Post List: Post Cards --}}
    <div class="space-y-6">
        @forelse ($posts as $post)
            <div class="w-full bg-white rounded-lg text-[#0F172A] space-y-6">
                <div class="flex gap-2 items-start">
                    {{-- Tutee Post Creator --}}
                    <div class="size-10">
                        <img
                            alt="User Avatar"
                            src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('images/default.jpg') }}"
                            class="rounded-full object-cover border border-[#F1F5F9] overflow-hidden"
                        />
                    </div>
                    <div class="w-full space-y-3">
                        <div class="flex items-center justify-between space-x-3">
                            <div class="flex gap-2 font-semibold">
                                {{ $post->tutees->user->fname .' '. $post->tutees->user->lname}}

                                {{-- Class Category --}}
                                <div>
                                    @if ($post->class_category == 'group')
                                        <x-wui-badge flat warning label="{{ $post->class_category }}" />
                                    @else
                                        <x-wui-badge flat purple label="{{ $post->class_category }}" />
                                    @endif

                                    @foreach (array_filter(json_decode($post->class_fields, true)) as $field)
                                        <x-wui-badge flat gray label="{{ $field }}" />
                                    @endforeach
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            @if ($post->tutees->user->id == Auth::id())
                                <div class="ml-auto">
                                    <x-wui-dropdown>
                                        <x-wui-dropdown.header class="font-semibold" label="Actions">
                                            <x-wui-dropdown.item wire:navigate target="_blank" href="{{ route('edit-post', $post->id) }}"
                                                icon='pencil-alt' label="Edit" />
                                            <x-wui-dropdown.item wire:click='deletePostModal({{ $post->id }})'
                                                icon='trash' label="Delete" />
                                        </x-wui-dropdown.header>
                                    </x-wui-dropdown>
                                </div>
                            @endif
                        </div>

                        {{-- class desc --}}
                        <div class="flex justify-between items-center">

                            <div class="inline-flex items-center gap-3">
                                <p>{{ $post->post_desc }}</p>
                            </div>
                        </div>

                        {{-- posted on --}}
                        <div class="flex gap-2 items-center text-[#64748B] text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p>Posted on {{ $post->created_at->format('l, F d Y g:i A') }}</p>
                        </div>

                        <hr class="h-px my-8 bg-gray-200 border-0">

                        {{-- buttons --}}
                        <div class="flex items-center space-x-2">
                            <x-primary-button class="w-full" wire:click='viewPostModal({{ $post->id }})' wireTarget='viewPostModal({{ $post->id }})'>
                                View Post
                            </x-primary-button>

                            <x-tertiary-button class="py-[6px] px-4 rounded-md flex items-center">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"
                                    fill="#292D32"  class="icon icon-tabler icons-tabler-filled icon-tabler-message">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M18 3a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-4.724l-4.762 2.857a1 1 0 0 1 -1.508 -.743l-.006 -.114v-2h-1a4 4 0 0 1 -3.995 -3.8l-.005 -.2v-8a4 4 0 0 1 4 -4zm-4 9h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m2 -4h-8a1 1 0 1 0 0 2h8a1 1 0 0 0 0 -2" />
                                </svg>
                            </x-tertiary-button>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="h-px my-2 bg-gray-200 border-0">
        @empty
            <div class="flex flex-col gap-3 justify-center items-center w-full">
                <img class="size-60" src="{{ asset('images/empty_class.svg') }}" alt="">
                <p class="font-semibold text-xl">No Posts</p>
            </div>
        @endforelse

        {{-- loading state --}}
        <div x-intersect.full.threshold.50='$wire.loadMore()'>
            <div wire:loading wire:target="loadMore" class="w-full flex flex-col bg-white rounded-xl">
                <div class="flex flex-auto flex-col justify-center items-center">
                    <div class="flex justify-center">
                        <div class="animate-spin inline-block size-7 border-[3px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500" role="status" aria-label="loading">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

    @include('livewire.pages.tutee.post_components.modals.view_tutee_post')
</section>
