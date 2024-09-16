<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TuteeNotification;
use App\Models\TutorNotification;
use Illuminate\Support\Facades\Auth;

class NotificationIcon extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        $userType = Auth::user()->user_type;
        
        if ($userType === 'tutee') {
            $this->unreadCount = TuteeNotification::where('read', false)->count();
        } elseif ($userType === 'tutor') {
            $this->unreadCount = TutorNotification::where('read', false)->count();
        }
    }

    public function render()
    {
        return view('livewire.pages.notification-icon');
    }
}

