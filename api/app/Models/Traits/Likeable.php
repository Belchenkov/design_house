<?php


namespace App\Models\Traits;


use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Likeable
{
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function like()
    {
        if (! auth()->check()) {
            return false;
        }

        // check if user has already liked the design
        if ($this->isLikedByUser(auth()->id())) {
            return false;
        }

        $this->likes()->create(['user_id' => auth()->id()]);
    }

    private function isLikedByUser(?int $user_id): bool
    {
        return (bool) $this->likes()
            ->where('user_id', $user_id)
            ->count();
    }
}
