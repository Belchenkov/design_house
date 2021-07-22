<?php

namespace App\Models;

use App\Models\Traits\Likeable;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

/**
 * Class Design
 * @package App\Models
 * @property int user_id
 * @property string image
 * @property string title
 * @property string description
 * @property string slug
 * @property int team_id
 * @property bool close_to_comment
 * @property bool is_live
 * @property bool upload_successful
 * @property string disk
 * @property Likeable likes
 */
class Design extends Model
{
    use Taggable, Likeable;

    protected $fillable = [
        'user_id',
        'image',
        'title',
        'description',
        'slug',
        'team_id',
        'close_to_comment',
        'is_live',
        'upload_successful',
        'disk'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->orderBy('created_at');
    }

    public function getImagesAttribute(): array
    {
        return [
            'thumbnail' => $this->getImagePath('thumbnail'),
            'large' => $this->getImagePath('large'),
            'original' => $this->getImagePath('original')
        ];
    }

    protected function getImagePath($size): string
    {
        return Storage::disk($this->disk)
            ->url('uploads/designs/' . $size . '/' . $this->image);
    }
}
