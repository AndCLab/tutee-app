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
        Schema::create('report_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')
                ->onDelete('cascade');

            // report class
            $table->foreignId('class_id')
                ->nullable()
                ->constrained('classes')
                ->onDelete('cascade');

            // report post
            $table->foreignId('post_id')
                ->nullable()
                ->constrained('posts')
                ->onDelete('cascade');

            $table->string('report_option');

            $table->string('comment')->nullable();
            $table->enum('status', [
                'Pending',
                'Approved',
                'Not Approved'
            ])->default('Pending');
            $table->timestamp('date_reported')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_contents');
    }
};
