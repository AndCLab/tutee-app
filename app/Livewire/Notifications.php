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
    public $notificationId;
    public $unreadCount;
    public $pages = 5; // Number of notifications per load

    public function mount()
    {
        $userType = Auth::user()->user_type;
        if ($userType === 'tutee') {
            $this->unreadCount = TuteeNotification::where('read', false)->count();
        } elseif ($userType === 'tutor') {
            $this->unreadCount = TutorNotification::where('read', false)->count();
        } else {
            $this->unreadCount = 0;
        }
    
        $this->loadNotifications();
    }

    public function loadNotifications($pages = 5)
    {
        $userType = Auth::user()->user_type;
        $this->notifications = Auth::user()->notifications()->get()->toArray();
    
        if ($userType === 'tutee') {
            $this->notifications = TuteeNotification::orderBy('date', 'desc')
                ->take($pages)
                ->get(['id', 'content', 'date', 'read'])  // Now, 'read' is a valid field
                ->toArray();

            // Count unread notifications
            $this->unreadCount = TuteeNotification::where('read', false)->count();
        } elseif ($userType === 'tutor') {
            $this->notifications = TutorNotification::orderBy('date', 'desc')
                ->take($pages)
                ->get(['id', 'content', 'date', 'read'])  // Now, 'read' is a valid field
                ->toArray();

            // Count unread notifications
            $this->unreadCount = TutorNotification::where('read', false)->count();
        } else {
            $this->notifications = [];
            $this->unreadCount = 0; // No notifications
        }
    }
    


    public function loadMore()
    {
        $this->pages += 5; // Increase the number of notifications to load
        $this->loadNotifications($this->pages); // Load more notifications
    }

    
    public function markAsRead($notificationId)
    {
        $userType = Auth::user()->user_type;

        if ($userType === 'tutee') {
            $notification = TuteeNotification::find($notificationId);
        } elseif ($userType === 'tutor') {
            $notification = TutorNotification::find($notificationId);
        } else {
            return; // Handle case where the user is neither a tutor nor a tutee
        }

        if ($notification && !$notification->read) {
            $notification->read = true;
            $notification->save();
        }

            // Your existing logic to mark notifications as read
        // After marking as read, refresh the unread count
        $this->loadNotifications();
    }
    

    public function render()
    {
        return view('livewire.pages.notifications');
    }
}