<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TuteeNotification;
use App\Models\TutorNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Notifications extends Component
{
    public $notifications = [];
    public $unreadCount;
    public $pages = 5;

    public function mount()
    {    
        $this->unreadCount = 0;
        $this->loadNotifications();
    }

    public function loadNotifications($pages = 5)
    {
        $user = Auth::user();
        $userType = $user->user_type;
    
        if ($userType === 'tutee') {
            $notifications = TuteeNotification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc') // Use created_at for sorting
                ->take($pages)
                ->select('id', 'content', 'created_at', 'read', 'read_at', 'type');
        } elseif ($userType === 'tutor') {
            $notifications = TutorNotification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc') // Use created_at for sorting
                ->take($pages)
                ->select('id', 'content', 'created_at', 'read', 'read_at', 'type');
        } else {
            $notifications = collect();
        }
    
        // Group notifications by created_at date
        $this->notifications = $notifications
            ->get()
            ->groupBy(function($notification) {
                return \Carbon\Carbon::parse($notification['created_at'])->format('F d, Y');
            })
            ->toArray();
    
        $this->updateUnreadCount();
    }
    

    

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        $userType = $user->user_type;
    
        if ($userType === 'tutee') {
            $notification = TuteeNotification::where('user_id', $user->id)->find($notificationId);
        } elseif ($userType === 'tutor') {
            $notification = TutorNotification::where('user_id', $user->id)->find($notificationId);
        } else {
            return;
        }
    
        Log::info("Notification found: ", ['notification' => $notification]);
    
        if ($notification && !$notification->read) {
            Log::info("Updating notification: ", ['id' => $notification->id]);
            // Update read status
            $notification->read = true;
            $notification->read_at = now();
            
            // Save without timestamps (since timestamps are disabled in the model)
            $notification->saveQuietly();
    
            // Update the read status in the notifications array
            foreach ($this->notifications as $date => &$dateGroup) {
                foreach ($dateGroup as &$notif) {
                    if ($notif['id'] === $notificationId) {
                        $notif['read'] = true;
                        break 2;
                    }
                }
            }
        }
    
        Log::info("Unread count updated.");
        $this->updateUnreadCount();
    }
    
    
    
    

    public function updateUnreadCount()
    {
        $user = Auth::user();
        $userType = $user->user_type;

        if ($userType === 'tutee') {
            $this->unreadCount = TuteeNotification::where('user_id', $user->id)
                ->where('read', false)
                ->count();
        } elseif ($userType === 'tutor') {
            $this->unreadCount = TutorNotification::where('user_id', $user->id)
                ->where('read', false)
                ->count();
        } else {
            $this->unreadCount = 0;
        }
    }

    public function loadMore()
    {
        $this->pages += 5;
        $this->loadNotifications($this->pages);
    }

    public function render()
    {
        return view('livewire.pages.notifications');
    }
}
