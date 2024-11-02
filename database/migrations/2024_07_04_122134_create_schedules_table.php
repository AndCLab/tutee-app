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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->date('initial_start_date')->nullable();
            $table->time('start_time');
            $table->integer('tutor_id')->unsigned();
            $table->time('end_time');
            $table->boolean('never_end')->default(0);
            $table->enum('repeat_every', [
                'once',
                'days',
                'months',
                'weeks',
                'weekdays',
                'custom'
            ])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
