<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Team
 * @package App\Models
 * @property int id
 */
class Team extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
        'slug'
    ];

    protected static function boot(): void
    {
        parent::boot();

        // when team is created, add current user as team member
        static::created(function(self $team) {
           //auth()->user()->teams()->attach($team->id);
            $team->members()->attach(auth()->id());
        });

        // when team is deleted, clear all members in team
        static::deleting(function(self $team) {
            $team->members()->sync([]);
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function designs(): HasMany
    {
        return $this->hasMany(Design::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * @param string $email
     * @return bool
     */
    public function hasPendingInvite(string $email): bool
    {
        return (bool)$this->invitations()
            ->where('recipient_email', $email)
            ->count();
    }

    public function hasUser(User $user): bool
    {
        return (bool)$this->members()
            ->where('user_id', $user->id)
            ->first();
    }
}
