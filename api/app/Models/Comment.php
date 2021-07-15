<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'body',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        $this->belongsTo(User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }
}
