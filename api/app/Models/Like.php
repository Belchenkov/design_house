<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Like
 * @package App\Models
 * @property int user_id
 */
class Like extends Model
{
    protected $fillable = [
        'user_id'
    ];

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}
