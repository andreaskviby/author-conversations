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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('synopsis')->nullable();
            $table->string('genre')->nullable();
            $table->string('language', 10)->default('en');
            $table->json('characters')->nullable(); // Main characters description
            $table->json('settings')->nullable(); // Time period, location, etc.
            $table->text('themes')->nullable(); // Main themes of the book
            $table->integer('target_word_count')->nullable();
            $table->integer('current_word_count')->default(0);
            $table->decimal('completion_percentage', 5, 2)->default(0.00);
            $table->enum('status', ['planning', 'writing', 'reviewing', 'completed', 'published'])->default('planning');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->json('publishing_notes')->nullable(); // Feedback from the publishing expert
            $table->string('cover_image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
