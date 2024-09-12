<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTuteeNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('tutee_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->dateTime('date');
            $table->string('type'); // Type of notification (e.g., change, schedule)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tutee_notifications');
    }
}

