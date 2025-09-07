<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'bio',
        'personality_traits',
        'writing_style',
        'expertise_areas',
        'interests',
        'preferred_language',
        'avatar_url',
        'is_active',
    ];

    protected $casts = [
        'personality_traits' => 'array',
        'writing_style' => 'array',
        'expertise_areas' => 'array',
        'interests' => 'array',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function getPersonalityString(): string
    {
        return is_array($this->personality_traits) 
            ? implode(', ', $this->personality_traits)
            : '';
    }

    public function getExpertiseString(): string
    {
        return is_array($this->expertise_areas) 
            ? implode(', ', $this->expertise_areas)
            : '';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
