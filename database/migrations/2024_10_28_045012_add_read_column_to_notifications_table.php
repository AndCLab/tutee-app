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
        Schema::table('notifications', function (Blueprint $table) {
            $table->boolean('read')->default(false); // Add the 'read' column with a default value of false
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('read'); // Drop the 'read' column if rolled back
        });
    }
};
