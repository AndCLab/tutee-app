<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTuteeNotificationsTable extends Migration
{
// Example migration for `tutor_notifications` table
    public function up()
    {
        Schema::create('tutee_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Ensure this exists
            $table->string('title');
            $table->text('content');
            //$table->timestamp('date');
            $table->enum('type', ['venue', 'schedule', 'assignment']);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        
        
        });
    }


    public function down()
    {
        Schema::dropIfExists('tutee_notifications');
    }
}

