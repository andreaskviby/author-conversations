<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends Model
{
    protected $fillable = [
        'book_id',
        'conversation_id',
        'title',
        'chapter_number',
        'content',
        'summary',
        'word_count',
        'character_appearances',
        'is_completed',
        'notes',
        'order_index',
    ];

    protected $casts = [
        'character_appearances' => 'array',
        'is_completed' => 'boolean',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function updateWordCount(): void
    {
        $wordCount = str_word_count(strip_tags($this->content ?? ''));
        $this->update(['word_count' => $wordCount]);
        
        // Update parent book word count
        $this->book->updateWordCount();
    }

    public function getPreview(int $words = 50): string
    {
        if (!$this->content) {
            return '';
        }

        $contentWords = explode(' ', strip_tags($this->content));
        return implode(' ', array_slice($contentWords, 0, $words)) . 
               (count($contentWords) > $words ? '...' : '');
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }
}
