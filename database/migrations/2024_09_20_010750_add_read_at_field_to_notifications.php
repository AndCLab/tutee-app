<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReadAtFieldToNotifications extends Migration
{
    public function up()
    {
        Schema::table('tutee_notifications', function (Blueprint $table) {
            $table->timestamp('read_at')->nullable();
        });

        Schema::table('tutor_notifications', function (Blueprint $table) {
            $table->timestamp('read_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tutee_notifications', function (Blueprint $table) {
            $table->dropColumn('read_at');
        });

        Schema::table('tutor_notifications', function (Blueprint $table) {
            $table->dropColumn('read_at');
        });
    }
}