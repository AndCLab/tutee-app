<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class NotificationIcon extends Component
{
    public $unreadCountCurrentRole = 0;
    public $unreadCountOtherRole = 0;
    public $currentRole;
    public $totalNotifications;

    public function mount()
    {
        \Log::error('Notif Icon Mounting');
        $user = Auth::user();
        
        // Get the current role
        $this->currentRole = $user->user_type;
        \Log::info('Current Role:', ['current_role' => $this->currentRole]);


        $this->updateUnreadCount();
    }

    #[On('notificationNum-updated')]
    public function updateUnreadCount()
    {
        $this->dispatch('notificationCount-updated');
        
        \Log::error('Notif Numbers Updated');
        $user = Auth::user();

        // Fetch all unread notifications for this user
        $this->totalNotifications = Notification::where('user_id', $user->id)
                ->where('read', false)
                ->get();
    
            \Log::info('Total Notifications:', [
                'count' => $this->totalNotifications->count(),
                'user_id' => $user->id
            ]);

        // Determine the other role
        $otherRole = $this->currentRole === 'tutor' ? 'tutee' : 'tutor';

        // Filter notifications based on role
        if ($this->totalNotifications) {
            $this->unreadCountCurrentRole = $this->totalNotifications
                ->where('role', $this->currentRole)
                ->count();

            $this->unreadCountOtherRole = $this->totalNotifications
                ->where('role', $otherRole)
                ->count();
        }

        \Log::info('Unread count updated:', [
            'user_id' => $user->id,
            'current_role' => $this->currentRole,
            'total_notifications' => $this->totalNotifications->count(),
            'unread_count_current_role' => $this->unreadCountCurrentRole,
            'unread_count_other_role' => $this->unreadCountOtherRole,
        ]);

        
    }

    public function render()
    {
        return view('livewire.pages.notification-icon');
    }
}