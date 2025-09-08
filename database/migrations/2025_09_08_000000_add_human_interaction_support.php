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
        // Add human participant support to messages
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('author_id')->constrained()->nullOnDelete();
            $table->enum('sender_type', ['ai_author', 'human_user'])->default('ai_author')->after('user_id');
            
            // Update the type enum to include human message types
            $table->dropColumn('type');
        });
        
        // Add the updated type column with new options
        Schema::table('messages', function (Blueprint $table) {
            $table->enum('type', [
                'message', 
                'story_contribution', 
                'chapter_draft', 
                'revision_suggestion',
                'human_input',
                'human_pause',
                'human_resume'
            ])->default('message')->after('content');
        });
        
        // Add human interaction controls to conversations
        Schema::table('conversations', function (Blueprint $table) {
            $table->boolean('allows_human_interaction')->default(false)->after('status');
            $table->json('human_participants')->nullable()->after('participants');
            $table->timestamp('paused_at')->nullable()->after('last_message_at');
            $table->foreignId('paused_by_user_id')->nullable()->after('paused_at')->constrained('users')->nullOnDelete();
        });
        
        // Add indexes for new fields
        Schema::table('messages', function (Blueprint $table) {
            $table->index(['user_id', 'sender_type']);
            $table->index(['conversation_id', 'sender_type']);
        });
        
        Schema::table('conversations', function (Blueprint $table) {
            $table->index(['allows_human_interaction', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['paused_by_user_id']);
            $table->dropIndex(['allows_human_interaction', 'status']);
            $table->dropColumn([
                'allows_human_interaction',
                'human_participants', 
                'paused_at',
                'paused_by_user_id'
            ]);
        });
        
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id', 'sender_type']);
            $table->dropIndex(['conversation_id', 'sender_type']);
            $table->dropColumn(['user_id', 'sender_type']);
            
            // Revert type enum to original
            $table->dropColumn('type');
        });
        
        Schema::table('messages', function (Blueprint $table) {
            $table->enum('type', ['message', 'story_contribution', 'chapter_draft', 'revision_suggestion'])->default('message')->after('content');
        });
    }
};