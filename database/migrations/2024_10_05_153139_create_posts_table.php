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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutee_id')->constrained('tutee')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('post_title');
            $table->json('class_fields');
            $table->date('class_date');
            $table->decimal('class_fee')->default(0);
            $table->enum('class_category', ['individual', 'group']);
            $table->enum('class_type', ['virtual', 'physical']);
            $table->string('class_location');
            $table->boolean('post_created')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
