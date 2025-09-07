<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'author_id',
        'content',
        'type',
        'metadata',
        'context_summary',
        'word_count',
        'is_processed',
        'is_flagged',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_processed' => 'boolean',
        'is_flagged' => 'boolean',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function updateWordCount(): void
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $this->update(['word_count' => $wordCount]);
    }

    public function getPreview(int $chars = 100): string
    {
        return strlen($this->content) > $chars 
            ? substr(strip_tags($this->content), 0, $chars) . '...'
            : strip_tags($this->content);
    }

    public function isStoryContribution(): bool
    {
        return in_array($this->type, ['story_contribution', 'chapter_draft']);
    }

    public function scopeUnprocessed($query)
    {
        return $query->where('is_processed', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForConversation($query, $conversationId)
    {
        return $query->where('conversation_id', $conversationId);
    }

    protected static function booted()
    {
        static::created(function (Message $message) {
            $message->updateWordCount();
            $message->conversation->updateMessageCount();
        });
    }
}
