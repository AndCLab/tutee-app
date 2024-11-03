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
        Schema::create('recurring_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')
                ->constrained('schedules')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->date('dates');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_schedules');
    }
};
