<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

use Livewire\Attributes\Layout;
use WireUi\Traits\Actions;

class NotificationIcon extends Component
{
    use Actions;
    public $unreadCountCurrentRole = 0;
    public $unreadCountOtherRole = 0;
    public $currentRole;
    public $totalNotifications;
    public $shouldNotify = true; // Flag variable

    public function mount()
    {
        \Log::error('Notif Icon Mounting');
        $user = Auth::user();
        // Get the current role
        $this->currentRole = $user->user_type;
        \Log::info('Current Role:', ['current_role' => $this->currentRole]);



        $this->updateUnreadCount();
        // $this->dispatchUnreadCount();
    }


    // public function dispatchUnreadCount(){
    //     $this->dispatch('notificationCount-updated');
    //     \Log::info('Dispatching notificationCount-updated event');
    // }


    // #[On('notificationCount-updated')]


    // #[On('fetch-notifications')]
    public function updateUnreadCount()
    {

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

        // $this->dispatchUnreadCount();

        // Only notify if the flag is true
        // if ($this->shouldNotify) {
        //     $this->notifyUser ();
        // }
    }


    public function rendered()
    {
        if ($this->shouldNotify) {
            $this->notifyUser ();
        }
    }

    public function notifyUser()
    {
        \Log::info('User Notified Now');

        $unreadNotifs = "You have {$this->unreadCountCurrentRole} unread notifications";
        $this->notification([
            'title'       => 'Notification',
            'description' => $unreadNotifs,
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);

        $this->shouldNotify = false; // Set the flag to false after notifying
    }

    public function render()
    {
        return view('livewire.pages.notification-icon');
    }
}
