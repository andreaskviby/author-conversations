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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();
            $table->longText('content');
            $table->enum('type', ['message', 'story_contribution', 'chapter_draft', 'revision_suggestion'])->default('message');
            $table->json('metadata')->nullable(); // AI model used, response time, etc.
            $table->text('context_summary')->nullable(); // What was happening when this was sent
            $table->integer('word_count')->default(0);
            $table->boolean('is_processed')->default(false); // For chapter extraction
            $table->boolean('is_flagged')->default(false); // Content moderation
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
            $table->index(['author_id', 'type']);
            $table->index(['is_processed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
