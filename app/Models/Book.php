<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'synopsis',
        'genre',
        'language',
        'characters',
        'settings',
        'themes',
        'target_word_count',
        'current_word_count',
        'completion_percentage',
        'status',
        'is_published',
        'published_at',
        'publishing_notes',
        'cover_image_url',
    ];

    protected $casts = [
        'characters' => 'array',
        'settings' => 'array',
        'publishing_notes' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'completion_percentage' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('order_index');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function updateWordCount(): void
    {
        $totalWords = $this->chapters()->sum('word_count');
        $this->update([
            'current_word_count' => $totalWords,
            'completion_percentage' => $this->target_word_count > 0 
                ? min(100, ($totalWords / $this->target_word_count) * 100) 
                : 0
        ]);
    }

    public function isReadyForPublishing(): bool
    {
        return $this->completion_percentage >= 90;
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
