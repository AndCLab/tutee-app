<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TuteeNotification;
use App\Models\TutorNotification;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    public $notifications;
    public $showAll = false; // Initialize showAll

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $userType = Auth::user()->user_type;

        if ($userType === 'tutee') {
            $this->notifications = TuteeNotification::orderBy('date', 'desc')->get();
        } elseif ($userType === 'tutor') {
            $this->notifications = TutorNotification::orderBy('date', 'desc')->get();
        } else {
            $this->notifications = collect(); // Empty collection
        }
    }

    public function toggleShowAll()
    {
        $this->showAll = !$this->showAll; // Toggle between showing all or limited
    }

    public function render()
    {
        return view('livewire.pages.notifications', [
            'notifications' => $this->notifications,
            'showAll' => $this->showAll, // Pass showAll to the view
        ]);
    }
}


