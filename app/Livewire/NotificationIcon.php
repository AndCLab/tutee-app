<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class NotificationIcon extends Component
{
    public $unreadCountCurrentRole = 0; // Unread count for the current role
    public $unreadCountOtherRole = 0; // Unread count for the other role
    public $currentRole; // Store the current role

    public function mount()
    {
        \Log::error('Notif Icon Mounting');
        $user = Auth::user();

        // Determine the current role from user_type
        $this->currentRole = $user->user_type; // This will be 'tutor' or 'tutee'
        \Log::info('Current Role:', ['current_role' => $this->currentRole]);

        $this->updateUnreadCount();
    }

    #[On('notificationNum-updated')]
    public function updateUnreadCount()
    {
        \Log::error('Notif Numbers Updated');
        $user = Auth::user();

        // Determine the other role
        $otherRole = $this->currentRole === 'tutor' ? 'tutee' : 'tutor';

        // Fetch unread count for the current role
        $this->unreadCountCurrentRole = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', $this->getNotifiableTypeForRole($this->currentRole))
            ->where('read', false)
            ->count();

        // Fetch unread count for the other role
        $this->unreadCountOtherRole = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', $this->getNotifiableTypeForRole($otherRole))
            ->where('read', false)
            ->count();

        \Log::info('Unread count updated:', [
            'user_id' => $user->id,
            'current_role' => $this->currentRole,
            'unread_count_current_role' => $this->unreadCountCurrentRole,
            'unread_count_other_role' => $this->unreadCountOtherRole,
        ]);
    }

    // Helper method to get the notifiable type based on the current role
    protected function getNotifiableTypeForRole($role)
    {
        switch ($role) {
            case 'tutee':
                return 'App\Models\Tutee'; // Adjust this to your Tutee model namespace
            case 'tutor':
                return 'App\Models\Tutor'; // Adjust this to your Tutor model namespace
            default:
                return null; // Handle unknown roles as needed
        }
    }

    public function render()
    {
        return view('livewire.pages.notification-icon');
    }
}
