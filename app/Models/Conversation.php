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
        'allows_human_interaction',
        'message_count',
        'last_message_at',
        'participants',
        'human_participants',
        'paused_at',
        'paused_by_user_id',
        'conversation_settings',
    ];

    protected $casts = [
        'participants' => 'array',
        'human_participants' => 'array',
        'conversation_settings' => 'array',
        'allows_human_interaction' => 'boolean',
        'last_message_at' => 'datetime',
        'paused_at' => 'datetime',
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

    public function pausedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paused_by_user_id');
    }

    public function getHumanParticipants()
    {
        if (!$this->human_participants) {
            return collect();
        }

        return User::whereIn('id', $this->human_participants)->get();
    }

    public function isPaused(): bool
    {
        return $this->status === 'paused' && $this->paused_at !== null;
    }

    public function pauseForHuman(User $user): void
    {
        $this->update([
            'status' => 'paused',
            'paused_at' => now(),
            'paused_by_user_id' => $user->id,
        ]);
    }

    public function resume(): void
    {
        $this->update([
            'status' => 'active',
            'paused_at' => null,
            'paused_by_user_id' => null,
        ]);
    }

    public function addHumanParticipant(User $user): void
    {
        $humanParticipants = $this->human_participants ?? [];
        if (!in_array($user->id, $humanParticipants)) {
            $humanParticipants[] = $user->id;
            $this->update([
                'human_participants' => $humanParticipants,
                'allows_human_interaction' => true,
            ]);
        }
    }

    public function removeHumanParticipant(User $user): void
    {
        $humanParticipants = $this->human_participants ?? [];
        $humanParticipants = array_values(array_filter($humanParticipants, fn($id) => $id !== $user->id));
        
        $this->update([
            'human_participants' => $humanParticipants,
            'allows_human_interaction' => count($humanParticipants) > 0,
        ]);
    }
}
