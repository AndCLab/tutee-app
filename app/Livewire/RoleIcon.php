<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import the Log facade

class RoleIcon extends Component
{
    public $otherUnreadCount = 0;
    public $unreadCountCurrentRole = 0;
    public $userType;  // Declare the userType property
    public $totalNotifications = 0;

    public function mount()
    {
        $user = Auth::user();
        $this->userType = $user->user_type;  // Assign user_type to the public property

        // Log the user type
        Log::info('User  Type:', ['user_type' => $this->userType]);

        // Update unread counts
        $this->updateUnreadCount();
    }

    public function updateUnreadCount()
    {
        $user = Auth::user();

        // Fetch total unread notifications for the user
        $this->totalNotifications = Notification::where('user_id', $user->id)
            ->where('read', false)
            ->count();

        Log::info('Total Unread Notifications:', ['total_count' => $this->totalNotifications]);

        // Count unread notifications for the current role
        // $this->unreadCountCurrentRole = Notification::where('user_id', $user->id)
        //     ->where('read', false)
        //     ->where('role', $this->userType) // Assuming 'role' field is present
        //     ->count();

        // Count unread notifications for the other role
        $otherRole = $this->userType === 'tutor' ? 'tutee' : 'tutor';
        $this->otherUnreadCount = Notification::where('user_id', $user->id)
            ->where('read', false)
            ->where('role', $otherRole) // Assuming 'role' field is present
            ->count();

        // Log the counts
        Log::info('Unread Notifications Count Updated:', [
            'user_id' => $user->id,
            'current_role' => $this->userType,
            'total_notifications' => $this->totalNotifications,
            // 'unread_count_current_role' => $this->unreadCountCurrentRole,
            'unread_count_other_role' => $this->otherUnreadCount,
        ]);
    }

    public function render()
    {
        return view('livewire.pages.role-icon');
    }
}
