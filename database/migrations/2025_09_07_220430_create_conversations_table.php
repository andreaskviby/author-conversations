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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('topic')->nullable(); // What they're discussing
            $table->text('context')->nullable(); // Current story context
            $table->enum('status', ['active', 'paused', 'completed', 'archived'])->default('active');
            $table->integer('message_count')->default(0);
            $table->timestamp('last_message_at')->nullable();
            $table->json('participants')->nullable(); // Array of author IDs participating
            $table->json('conversation_settings')->nullable(); // AI parameters, focus areas, etc.
            $table->timestamps();

            $table->index(['book_id', 'status']);
            $table->index(['last_message_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
