<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class Messages extends Component
{
    public $conversations = [];
    public $pages = 1;
    public $perPage = 5;
    public $hasMore = true;

    public function mount()
    {
        $this->loadConversations();
    }

    public function loadConversations()
    {
        $userId = Auth::id();

        // Fetch conversations with a limit (pagination style)
        $newConversations = Conversation::where(function ($query) use ($userId) {
            $query->where('user_id1', $userId)
                ->orWhere('user_id2', $userId);
        })->with('lastMessage')
            ->orderBy('last_message_id', 'desc')
            ->skip(($this->pages - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();

        // Append new conversations
        if ($newConversations->isNotEmpty()) {
            $this->conversations = array_merge($this->conversations, $newConversations->all());
        }

        // Check if there are more conversations to load
        if ($newConversations->count() < $this->perPage) {
            $this->hasMore = false;
        }
    }

    public function loadMore()
    {
        if ($this->hasMore) {
            $this->pages++;
            $this->loadConversations();
        }
    }

    public function render()
    {
        return view('livewire.pages.message.messages');
    }
}

