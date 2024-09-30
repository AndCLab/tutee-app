<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

class Bookmarks extends Component
{
    protected $listeners = ['bookmarkTutor'];

    public $bookmarkedTutors = [];
    public $pages = 5;

    
    public function mount()
    {
        // Load the user's bookmarked tutors on component mount
        $this->loadBookmarkedTutors();
        $this->loadBookmarks();
    }

    // Toggle bookmark: add if not exists, or remove if exists
    public function bookmarkTutor($tutorId)
    {
        $user = auth()->user();

        // Check if the tutor is already bookmarked
        $existingBookmark = Bookmark::where('user_id', $user->id)
                                    ->where('tutor_id', $tutorId)
                                    ->first();

        if ($existingBookmark) {
            // Remove the bookmark if it exists
            $existingBookmark->delete();
            session()->flash('message', 'Tutor removed from bookmarks.');
        } else {
            // Add the bookmark if it doesn't exist
            Bookmark::create([
                'user_id' => $user->id,
                'tutor_id' => $tutorId,
            ]);
            session()->flash('message', 'Tutor added to bookmarks.');
        }

        // Refresh the bookmarks after the change
        $this->loadBookmarkedTutors();
    }

    // Load bookmarked tutors from the database
    public function loadBookmarkedTutors()
    {
        // This will load bookmarked tutors with their user details (e.g. image, name)
        $this->bookmarkedTutors = Bookmark::with('tutor.user')
                                          ->where('user_id', Auth::id())
                                          ->get();
    }
    
    public function loadMore()
    {
        $this->pages += 5;
        $this->loadBookmarks($this->pages);
    }

    public function removeBookmark($bookmarkId)
    {
        $user = auth()->user();

        // Find the bookmark
        $bookmark = Bookmark::where('user_id', $user->id)
                            ->where('id', $bookmarkId)
                            ->first();

        // Remove the bookmark if it exists
        if ($bookmark) {
            $bookmark->delete();
            session()->flash('message', 'Bookmark removed.');
        }

        // Refresh the bookmarks after the change
        $this->loadBookmarkedTutors();
    }

    public function loadBookmarks($pages = 5)
    {
        $user = Auth::user();
        $bookmarkedTutors = Bookmark::with('tutor.user')
                                     ->where('user_id', $user->id)
                                     ->orderBy('created_at', 'desc')
                                     ->take($pages)
                                     ->get();
    
        $this->bookmarkedTutors = $bookmarkedTutors;
    }

    public function selectTutorFromBookmarks($tutorId)
    {
        // Trigger a custom browser event with the tutorId
        $this->dispatchBrowserEvent('tutorSelected', ['tutorId' => $tutorId]);
    }
    


    public function render()
    {
        return view('livewire.pages.bookmark.bookmarks');
    }

}

