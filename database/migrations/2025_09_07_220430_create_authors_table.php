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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('bio')->nullable();
            $table->json('personality_traits')->nullable(); // e.g. ["creative", "analytical", "humorous"]
            $table->json('writing_style')->nullable(); // e.g. {"tone": "formal", "complexity": "high", "genre_preferences": ["mystery", "thriller"]}
            $table->json('expertise_areas')->nullable(); // e.g. ["psychology", "history", "science"]
            $table->json('interests')->nullable(); // e.g. ["technology", "travel", "cooking"]
            $table->string('preferred_language', 10)->default('en');
            $table->string('avatar_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
