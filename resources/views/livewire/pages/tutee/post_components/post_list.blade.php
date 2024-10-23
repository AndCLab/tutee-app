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
use App\Models\Blacklist;
use App\Models\ReportContent;

new #[Layout('layouts.app')] class extends Component {
    use Actions;

    #[Url(as: 'sort_by')]
    public $sort_by = 'desc';

    public $getPost;
    public $availableFields = [];
    public $report_post;

    // properties
    public $selectedOption;
    public $comment;

    // models

    // filters
    public $pricing;
    public $class_category;
    public $class_fields = [];
    public $time_avail;

    // states
    public $showDeletePostModal;
    public $showTuteePost;
    public $deletePostId;
    public $pages = 5;

    public $showReportPostModal;
    public $showPostModal;

    public function mount()
    {
        $this->availableFields = Fields::where('user_id', Auth::id())
                                ->where('active_in', Auth::user()->user_type)
                                ->get(['field_name'])
                                ->toArray();
    }


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

    // report post content
    public function reportPostModal($reportPostId)
    {
        $this->showReportPostModal = true;
        $this->report_post = Post::findOrFail($reportPostId);
    }

    public function submitPostReport()
    {
        $rule = $this->validate([
            'comment' => ['nullable', 'max:255', 'string'],
            'selectedOption' => ['required']
        ], [
            'comment.max' => 'The comment may not be greater than 255 characters.',
            'comment.string' => 'The comment must be a valid string.',
            'selectedOption.required' => 'Please choose a report type.',
        ]);

        $isReported = ReportContent::where('reporter', Auth::id())
                                    ->where('post_id', $this->report_class->id)
                                    ->exists();

        if ($isReported) {
            $this->notification([
                'title' => 'Already Reported',
                'description' => 'We\'re still reviewing your feedback.',
                'icon' => 'error',
                'timeout' => 2500,
            ]);

            return;
        }

        $reported = ReportContent::create([
            'reporter' => Auth::id(),
            'post_id' => $this->report_post->id,
            'report_option' => $this->selectedOption,
        ]);

        $reported_user = $reported->tutees->user_id;

        // chgeck if found in blacklist
        $blacklist = Blacklist::where('reported_user', $reported_user)->first();

        if ($blacklist) {
            // increment if found
            $blacklist->increment('report_count');
        } else {
            // create a new entry with report_count = 1
            Blacklist::create([
                'reported_user' => $reported_user,
                'report_count' => 1,
            ]);
        }

        if ($this->comment) {
            $reported->comment = $this->comment;
            $reported->save();
        }

        $this->notification([
            'title' => 'Content reported',
            'description' => 'Thank you! We\'ll review your feedback.',
            'icon' => 'success',
            'timeout' => 2500,
        ]);

        $this->showReportPostModal = false;

        $this->reset('comment');
    }

    #[On('post-created')]
    public function with(): array
    {
        $getFields = Fields::where('user_id', Auth::id())
                            ->where('active_in', Auth::user()->user_type)
                            ->pluck('field_name')
                            ->toArray();

        $tutee_id = Tutee::where('user_id', Auth::id())->pluck('id')->first();

        // Retrieve all posts and filter them into a collection
        $filteredPosts = Post::all()->filter(function ($post) use ($tutee_id, $getFields) {
            // Decode the class_fields JSON
            $classFields = json_decode($post->class_fields, true);

            if (Auth::user()->user_type == 'tutee') {
                // Include tutee posts based on tutee ID
                return $post->tutee_id == $tutee_id;
            } elseif (Auth::user()->user_type == 'tutor') {
                // Check if any of the fields matched from the tutor
                return !empty(array_intersect($classFields, $getFields));
            }

            return false; // In case user_type is neither tutee nor tutor
        });

        // Apply additional filtering based on other conditions
        $filteredPosts = $filteredPosts->when($this->class_fields, function ($query) {
                return $query->filter(function ($post) {
                    return !empty(array_intersect(json_decode($post->class_fields, true), $this->class_fields));
                });
            })
            ->when($this->pricing, function ($query) {
                return $query->where('class_fee', '<=', $this->pricing);
            })
            ->when($this->class_category, function ($query) {
                return $query->where('class_category', $this->class_category);
            })
            ->when($this->time_avail, function ($query) {
                return $query->where('class_date', '=', $this->time_avail);
            })
            ->sortByDesc('created_at')
            ->take($this->pages);

        return [
            'posts' => $filteredPosts,
        ];
    }


}; ?>

<section>
    <x-slot name="header">
    </x-slot>

    @if (Auth::user()->user_type == 'tutor')
        <div class="md:flex items-center space-x-3 w-full pb-5">
            <div class="space-y-2 col-span-1 w-full md:col-span-5">
                {{-- interests --}}
                <div class="w-full">
                    <x-wui-select wire:model.live="class_fields" placeholder="Class Fields" multiselect shadowless>
                        @foreach ($availableFields as $field)
                            <x-wui-select.option label="{{ $field['field_name'] }}"
                                value="{{ $field['field_name'] }}" />
                        @endforeach
                    </x-wui-select>
                </div>

                <div class="md:inline-flex space-y-2 w-full md:space-y-0 gap-2">
                    {{-- pricing --}}
                    <div class="w-full md:w-[40%]">
                        <x-wui-inputs.currency wire:model.live.debounce.250ms='pricing' icon="cash" placeholder="Pricing" shadowless />
                    </div>

                    {{-- sort by --}}
                    <div class="w-full md:w-[60%]">
                        <x-wui-select wire:model.live='class_category' placeholder="Sort by Class Type" shadowless>
                            <x-wui-select.option label="Group Class" value="group" />
                            <x-wui-select.option label="Individual Class" value="individual" />
                        </x-wui-select>
                    </div>

                    {{-- time avail --}}
                    <div class="w-full">
                        <x-wui-datetime-picker
                            placeholder="Availability"
                            wire:model.live="time_avail"
                            parse-format="YYYY-MM-DD"
                            display-format='dddd, MMMM D, YYYY'
                            :min="now()"
                            without-time
                            shadowless
                        />
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Post List: Post Cards --}}
    <div class="space-y-6">
        @forelse ($posts as $post)
            <div class="w-full bg-white rounded-lg text-[#0F172A] space-y-6">
                <div class="flex gap-2 items-start">
                    {{-- Tutee Post Creator --}}
                    <div class="size-10">
                        <img
                            alt="User Avatar"
                            src="{{ $post->tutees->user->avatar ? Storage::url($post->tutees->user->avatar) : asset('images/default.jpg') }}"
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

    {{-- view tutee post --}}
    @include('livewire.pages.tutee.post_components.modals.view_tutee_post')

    {{-- report content --}}
    @include('livewire.pages.report_contents.report_post')

    <x-wui-notifications position="bottom-right" />

</section>
