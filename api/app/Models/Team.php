<?php

namespace App\MOdels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Team
 * @package App\MOdels
 * @property int id
 */
class Team extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
        'slug'
    ];

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

    public function hasUser(User $user): bool
    {
        return (bool)$this->members()
            ->where('user_id', $user->id)
            ->first();
    }
}
