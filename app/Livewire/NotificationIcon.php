<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TuteeNotification;
use App\Models\TutorNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class NotificationIcon extends Component
{
    public $unreadCount = 0;
    public $otherUnreadCount = 0;

    public function mount()
    {
        \Log::error('Notif Icon Mounting');
        $this->updateUnreadCount();
    }

    #[On('notificationNum-updated')]
    public function updateUnreadCount()
    {
        \Log::error('Notif Numbers Updated');
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


    public function render()
    {
        return view('livewire.pages.notification-icon');
    }
}

