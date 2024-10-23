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
        Schema::create('class_rosters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('tutee_id')->constrained('tutee')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('proof_of_payment')->nullable();
            $table->enum('attendance', [
                'Pending',
                'Absent',
                'Present'
            ])->default('Pending');
            $table->enum('payment_status', [
                'Pending',
                'Approved',
                'Not Approved'
            ])->default('Pending');
            $table->boolean('rated')->default(0);
            $table->timestamp('date_of_upload')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_rosters');
    }
};
