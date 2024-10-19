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

    public string $post_desc ='';
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

        $this->posts = Post::where('post_created', 1)
                                    ->orderBy('created_at', $this->sort_by)
                                    ->get();

        $this->getFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])
                                ->toArray();

        $this->isEmptyPost = $this->posts->isEmpty();
    }

    public function displayList($isNotEmpty)
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
    <div class="space-y-6">
        @forelse ($posts as $post)
            <div class="w-full bg-white p-6 shadow-lg rounded-lg text-[#0F172A] space-y-6" wire:loading.remove>
                <div class="space-y-3">
                    <div class="flex items-center justify-between space-x-3">
                        <div class="flex items-center space-x-3">
                            {{-- Tutee Post Creator --}}
                            <img
                                alt="User Avatar"
                                src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('images/default.jpg') }}"
                                class="w-10 h-10 rounded-full object-cover border border-[#F1F5F9] overflow-hidden"
                            />
                            <strong class="block font-medium max-w-28 truncate">{{ $post->tutees->user->fname .' '. $post->tutees->user->lname}}</strong>
                        </div>

                        {{-- Action Buttons --}}
                        @if ($post->post_created == 1 && $post->tutee_id == $tutee->id)
                            <div class="ml-auto">
                                <x-wui-dropdown>
                                    <x-wui-dropdown.header class="font-semibold" label="Actions">
                                        <x-wui-dropdown.item wire:navigate href="{{ route('edit-post', $post->id) }}"
                                            icon='pencil-alt' label="Edit" />
                                        <x-wui-dropdown.item wire:click='deletePostModal({{ $post->id }})'
                                            icon='trash' label="Delete" />
                                    </x-wui-dropdown.header>
                                </x-wui-dropdown>
                            </div>
                        @endif
                    </div>

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

                    <div class="flex justify-between items-center">

                        <div class="inline-flex items-center gap-3">
                            <p>{{ $post->post_desc }}</p>
                        </div>
                    </div>

                    <div>
                        <p>
                            <strong>Desired Date:</strong> {{ $post->class_date }}
                        </p>
                        <p>
                            <strong>Estimated Fee:</strong> {{ $post->class_fee == 0.0 ? 'Free Class' : number_format($post->class_fee, 2) }}
                        </p>
                        <p>
                            <strong>Class Type:</strong> {{ ucfirst($post->class_type) }}
                        </p>
                        @if ($post->class_location)
                            <p>
                            <strong>Class Location:</strong> {{ $post->class_location }}
                            </p>
                        @endif
                    </div>

                    <div class="flex gap-2 items-center text-[#64748B] text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p>Posted on {{ $post->created_at->format('l jS \\of F Y h:i A') }}</p>
                    </div>

                    <div class="flex justify-end items-center space-x-2">
                        <x-primary-button type='' wireTarget='openPost'>
                            View Post
                        </x-primary-button>

                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded flex items-center">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"
                            fill="#292D32"  class="icon icon-tabler icons-tabler-filled icon-tabler-message">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M18 3a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-4.724l-4.762 2.857a1 1 0 0 1 -1.508 -.743l-.006 -.114v-2h-1a4 4 0 0 1 -3.995 -3.8l-.005 -.2v-8a4 4 0 0 1 4 -4zm-4 9h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m2 -4h-8a1 1 0 1 0 0 2h8a1 1 0 0 0 0 -2" />
                            </svg>
                        </button>
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
