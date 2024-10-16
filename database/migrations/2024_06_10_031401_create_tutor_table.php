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
        Schema::create('tutor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')
                ->onDelete('cascade');
            $table->string('bio')->nullable();
            $table->string('work')->nullable();
            // $table->string('degree')->nullable();
            $table->decimal('average_rating', 3, 1)->nullable();
            $table->enum('verify_status', ['not_verified', 'pending', 'verified'])->default('not_verified');
            // $table->json('work');
            $table->json('degree')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor');
    }
};
