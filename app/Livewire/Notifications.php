<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TuteeNotification;
use App\Models\TutorNotification;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Notifications extends Component
{
    public $notifications;
    public $pages = 5; // Number of notifications per load

    public function mount()
    {
        $this->loadNotifications($this->pages);
    }

    public function loadNotifications($pages = 5)
    {
        $userType = Auth::user()->user_type;

        if ($userType === 'tutee') {
            $this->notifications = TuteeNotification::orderBy('date', 'desc')
                ->take($pages)
                ->get()
                ->toArray();
        } elseif ($userType === 'tutor') {
            $this->notifications = TutorNotification::orderBy('date', 'desc')
                ->take($pages)
                ->get()
                ->toArray();
        } else {
            $this->notifications = []; // Empty array
        }
    }


    public function loadMore()
    {
        $this->pages += 5; // Increase the number of notifications to load
        $this->loadNotifications($this->pages); // Load more notifications
    }
    

    public function render()
    {
        return view('livewire.pages.notifications');
    }
}