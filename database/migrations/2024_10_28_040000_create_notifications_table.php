<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')
                ->onDelete('cascade'); // Reference to the user (tutor or tutee)
            $table->foreignId('class_id')->constrained('classes')
                ->onDelete('cascade'); // Reference to the class
            $table->foreignId('class_roster_id')->nullable()->constrained('class_rosters')
                ->onDelete('cascade'); // Reference to the class roster
            $table->foreignId('post_id')->nullable()->constrained('posts')
                ->onDelete('cascade'); // Reference to the posts
            $table->foreignId('review_id')->nullable()->constrained('reviews')
                ->onDelete('cascade'); // Reference to the reviews
            $table->foreignId('report_content_id')->nullable()->constrained('report_contents')
                ->onDelete('cascade'); // Reference to the report contents
            $table->foreignId('blacklist_id')->nullable()->constrained('blacklists')
                ->onDelete('cascade'); // Reference to the blacklists
            $table->foreignId('recurring_schedule_id')->nullable()->constrained('recurring_schedules')
                ->onDelete('cascade'); // Reference to the recurring schedules
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['joinClass', 'editClass', 'leaveClass', 'payment', 'attendance']);
            $table->enum('role', ['tutor', 'tutee']); // To differentiate between tutor and tutee notifications
            $table->string('notifiable_type'); // Add this line
            $table->unsignedBigInteger('notifiable_id'); // Add this line
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
