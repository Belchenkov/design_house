<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'chat_id',
        'body',
        'last_read'
    ];

    /**
     * @param $value
     * @return string|null
     */
    public function getBodyAttribute($value): ?string
    {
        if ($this->trashed()) {
            if (! auth()->check()) return null;

            return auth()->id() === $this->sender->id
                ? 'You deleted this message'
                : $this->sender->name . ' deleted this message';
        }

        return $value;
    }

    protected $touches = ['chat'];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
