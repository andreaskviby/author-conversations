<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'book_id',
        'title',
        'topic',
        'context',
        'status',
        'message_count',
        'last_message_at',
        'participants',
        'conversation_settings',
    ];

    protected $casts = [
        'participants' => 'array',
        'conversation_settings' => 'array',
        'last_message_at' => 'datetime',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function updateMessageCount(): void
    {
        $count = $this->messages()->count();
        $lastMessage = $this->messages()->latest()->first();
        
        $this->update([
            'message_count' => $count,
            'last_message_at' => $lastMessage?->created_at,
        ]);
    }

    public function getParticipantAuthors()
    {
        if (!$this->participants) {
            return collect();
        }

        return Author::whereIn('id', $this->participants)->get();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForBook($query, $bookId)
    {
        return $query->where('book_id', $bookId);
    }
}
