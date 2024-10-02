<?php

// RoleIcon.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TuteeNotification;
use App\Models\TutorNotification;
use Illuminate\Support\Facades\Auth;

class RoleIcon extends Component
{
    public $otherUnreadCount = 0;
    public $userType;  // Declare the userType property

    public function mount()
    {
        $user = Auth::user();
        $this->userType = $user->user_type;  // Assign user_type to the public property

        if ($this->userType === 'tutee') {
            $this->otherUnreadCount = TutorNotification::where('user_id', $user->id)
                ->where('read', false)
                ->count();
        } elseif ($this->userType === 'tutor') {
            $this->otherUnreadCount = TuteeNotification::where('user_id', $user->id)
                ->where('read', false)
                ->count();
        }
    }

    public function render()
    {
        return view('livewire.pages.role-icon');
    }
}


