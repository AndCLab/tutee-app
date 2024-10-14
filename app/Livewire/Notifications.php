<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TuteeNotification;
use App\Models\TutorNotification;
use App\Models\Classes;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Carbon\Carbon;

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

        if ($notification && !$notification->read) {
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


    #[On('classJoined')]
    public function classJoined($classId, $tutorName)
    {
        // Fetch the class and schedule information
        $class = \App\Models\Classes::findOrFail($classId);
        $schedule = $class->schedule; // Get the schedule associated with the class

        $startDate = Carbon::create($schedule->start_date);
        $formattedDate = $startDate->format('l jS \\of F Y g:i A');

        $content = "You have joined the class '{$class->class_name}' handled by {$tutorName}. The class is scheduled to start on {$formattedDate}.";

        TuteeNotification::create([
            'user_id' => Auth::id(),
            'title' => 'Class Joined', // Provide a title for the notification
            'content' => $content,
            'type' => 'venue',
            'created_at' => now(), // Set created_at field
            'updated_at' => now(), // Set updated_at field
        ]);

        // Flash a success message
        session()->flash('success', 'You have successfully joined the class!');

        $this->loadNotifications();
    }


    public function render()
    {
        return view('livewire.pages.notifications');
    }
}
