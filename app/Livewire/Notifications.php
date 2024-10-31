<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; // Import the On attribute
use App\Models\Notification;
use App\Models\Classes;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Schedule;
use App\Models\Registration;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\ClassJoined; // Import the ClassJoined event
use Carbon\Carbon;

use Livewire\Attributes\Layout;
use WireUi\Traits\Actions;


class Notifications extends Component
{
    use Actions;

    public $notifications = [];
    public $unreadCount;
    public $pages = 5;

    public function mount()
    {
        $this->unreadCount = 0 ;
        $this->loadNotifications();
        $this->updateUnreadCount();
    }

    public function dispatchLoading()
    {
        $this->dispatch('fetch-notifications');
    }

    #[On('fetch-notifications')]
    public function loadNotifications($pages = 5)
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc') // Use created_at for sorting
            ->take($pages)
            ->select('id', 'content', 'created_at', 'read', 'read_at', 'type');

        // Group notifications by created_at date
        $this->notifications = $notifications
            ->get()
            ->groupBy(function($notification) {
                return \Carbon\Carbon::parse($notification['created_at'])->format('F d, Y');
            })
            ->toArray();

            // $this->dispatch('fetch-notifications');
        // $this->updateUnreadCount();
    }

    #[On('class-joined')] // Listen for the class-joined event
    public function handleClassJoined($data)
    {
        // Get the current authenticated user
        $user = Auth::user();

        // Check if the user is authenticated
        if (!$user) {
            \Log::error('No authenticated user when trying to create notification.');
            return; // Handle the case where there is no authenticated user
        }

        \Log::info('Authenticated User:', ['user_id' => $user->id]);

        // Fetch the class information
        $class = Classes::findOrFail($data['class_id']); // No eager loading

        // Log the class data for debugging
        \Log::info('Class Data:', ['class' => $class]);

        // Retrieve the schedule for the class using the schedule_id from the dispatched event
        $schedule = Schedule::find($data['schedule_id']); // Use the schedule_id from the event data

        // Check if the schedule exists
        if (!$schedule) {
            \Log::error('No schedule found for class ID: ' . $data['class_id']);
            return; // Handle the case where there is no schedule
        }

        // Retrieve the recurring schedules for the found schedule
        $recurringSchedules = $schedule->recurring_schedule; // Assuming there's a one-to-many relationship

        // Check if there are any recurring schedules
        if ($recurringSchedules->isEmpty()) {
            \Log::error('No recurring schedules found for schedule ID: ' . $schedule->id);
            return; // Handle the case where there are no recurring schedules
        }

        // Find the specific recurring schedule based on the passed date
        $specificDate = $data['specific_date']; // This should be the date or ID you passed
        $specificSchedule = $recurringSchedules->firstWhere('dates', $specificDate); // Adjust based on how you pass the date

        // Check if the specific schedule was found
        if (!$specificSchedule) {
            \Log::error('No recurring schedule found for the specified date: ' . $specificDate);
            return; // Handle the case where the specific schedule does not exist
        }

        // Format the date for the notification
        $formattedDate = Carbon::parse($specificSchedule->dates)->format('l jS \\of F Y g:i A');

        // Retrieve the tutor using the tutor_id from the Classes model
        $tutor = Tutor::where('id', $class->tutor_id)->with('user')->first();

        // Check if the tutor exists
        if (!$tutor || !$tutor->user) {
            \Log::error('Tutor not found for class ID: ' . $data['class_id']);
            return; // Handle the case where the tutor does not exist
        }

        // Log the tutor data for debugging
        \Log::info('Tutor Data:', ['tutor_id' => $tutor->id, 'tutor_user' => $tutor->user]);

        // Construct the notification content for the tutee
        $className = $class->class_name ?? 'Unknown Class'; // Assuming class_name is the correct field
        $tuteeName = $user->name ?? 'Unknown Tutee'; // Get the authenticated user's name
        $tutorName = $tutor->user->fname ?? 'Unknown Tutor'; // Access the user's first name

        // Log the notification content data for tutee
        \Log::info('Notification Data for Tutee:', [
            'class_name' => $className,
            'tutee_name' => $tuteeName,
            'scheduled_date' => $formattedDate,
            'tutee_id' => $data['tutee_id'],
        ]);

        $contentForTutee = "{$className} with {$tutorName} is scheduled to start on {$formattedDate}.";

        // Create the notification for the tutee
        $tuteeNotification = Notification::create([
            'notifiable_id' => $data['tutee_id'], // Make sure this is valid
            'notifiable_type' => 'App\Models\Tutee',
            'user_id' => $user->id, // Ensure this is set correctly
            'class_id' => $data['class_id'], // Set the class_id
            'class_roster_id' => null, // Set to null if not applicable
            'post_id' => null, // Set to null if not applicable
            'review_id' => null, // Set to null if not applicable
            'report_content_id' => null, // Set to null if not applicable
            'blacklist_id' => null, // Set to null if not applicable
            'recurring_schedule_id' => $specificSchedule->id, // Link to the recurring schedule
            'title' => 'Class Scheduled', // Provide a title
            'content' => $contentForTutee, // Use the constructed content
            'read' => false,
            'type' => 'schedule', // Set a default type for scheduling
            'role' => 'tutee', // Set the role based on context
        ]);

        \Log::info('Tutee Notification Created:', ['notification_id' => $tuteeNotification->id]);

        // Construct the notification content for the tutor
        $contentForTutor = "{$tuteeName} just joined your {$className} scheduled at {$formattedDate}.";

        // Create the notification for the tutor
        $tutorNotification = Notification::create([
            'notifiable_id' => $tutor->id, // Make sure this is valid
            'notifiable_type' => 'App\Models\Tutor',
            'user_id' => $tutor->user->id, // Ensure this is set correctly
            'class_id' => $data['class_id'], // Set the class_id
            'class_roster_id' => null, // Set to null if not applicable
            'post_id' => null, // Set to null if not applicable
            'review_id' => null, // Set to null if not applicable
            'report_content_id' => null, // Set to null if not applicable
            'blacklist_id' => null, // Set to null if not applicable
            'recurring_schedule_id' => $specificSchedule->id, // Link to the recurring schedule
            'title' => 'Tutee Joined Class', // Provide a title
            'content' => $contentForTutor, // Use the constructed content
            'read' => false,
            'type' => 'schedule', // Set a default type for scheduling
            'role' => 'tutor', // Set the role based on context
        ]);

        \Log::info('Tutor Notification Created:', ['notification_id' => $tutorNotification->id]);

        $this->notification([
            'title'       => 'Success',
            'description' => 'You have a joined a class',
            'icon'        => 'success',
            'timeout'     => 2500,
        ]);
        // Reload notifications to include the new ones

        // $this->dispatchLoading();
        // $this->loadNotifications();
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);

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

        // $this->dispatch('fetch-notifications'); //no need, as notificationNum is updated when read because it refreshes and mounts
        // $this->updateUnreadCount();

        // Call the route function to determine where to redirect the user
        return $this->routeToPageBasedOnRole(Auth::user()->user_type, $notification);
    }

    #[On('notificationCount-updated')] // Listen for the update count event
    public function updateUnreadCount()
    {
        \Log::info('updateUnreadCount method triggered');

        $user = Auth::user();

        $this->unreadCount = Notification::where('user_id', $user->id)
            ->where('read', false)
            ->count();
    }

    public function loadMore()
    {
        $this->pages += 5;
        $this->loadNotifications($this->pages);
    }

    public function routeToPageBasedOnRole($userType, $notification)
    {
        // Customize based on the notification type
        if ($userType === 'tutor') {
            $className = $notification->class_name ?? '';  // Assumes class name is stored in notification data
            return redirect()->route('classes', ['search_class' => $className]);
        } elseif ($userType === 'tutee') {
            return redirect()->route('tutee.schedule');  // Default route for tutees
        } else {
            // Handle other roles or show a default page if the user has no role
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.pages.notifications');
    }
}
