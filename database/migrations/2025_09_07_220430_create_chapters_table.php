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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('conversation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->integer('chapter_number');
            $table->longText('content')->nullable();
            $table->text('summary')->nullable();
            $table->integer('word_count')->default(0);
            $table->json('character_appearances')->nullable(); // Which characters appear in this chapter
            $table->boolean('is_completed')->default(false);
            $table->text('notes')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();

            $table->unique(['book_id', 'chapter_number']);
            $table->index(['book_id', 'order_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
