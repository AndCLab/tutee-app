<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();  // Laravel uses UUID for notification IDs
            $table->string('type');  // Notification class name

            // Polymorphic relation: notifiable_type and notifiable_id
            $table->morphs('notifiable');  // This creates `notifiable_type` and `notifiable_id`

            $table->text('data');  // The actual notification data (usually stored as JSON)
            $table->timestamp('read_at')->nullable();  // When the notification was read
            $table->timestamps();  // Created_at, updated_at
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
