<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; // Import the Log facade

class ClassJoined
{
    use Dispatchable, SerializesModels;

    public $tuteeId;
    public $classId;

    public function __construct($tuteeId, $classId)
    {
        $this->tuteeId = $tuteeId;
        $this->classId = $classId;

        // Log the event creation
        Log::info('ClassJoined event created:', [
            'tutee_id' => $tuteeId,
            'class_id' => $classId,
        ]);
    }
}
