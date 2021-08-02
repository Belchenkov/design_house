<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property BelongsToMany participants
 * @property HasMany messages
 */
class Chat extends Model
{
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'participants');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function getLatestMessageAttribute(): HasMany
    {
        return $this->messages()->latest()->first();
    }

    public function isUnreadForUser(int $user_id): bool
    {
        return (bool)$this->messages()
            ->whereNull('last_read')
            ->where('user_id', '<>', $user_id)
            ->count();
    }

    public function markAsReadForUser(int $user_id): void
    {
        $this->messages()
            ->whereNull('last_read')
            ->where('user_id', '<>', $user_id)
            ->update([
                'last_read' => Carbon::now()
            ]);
    }
}

