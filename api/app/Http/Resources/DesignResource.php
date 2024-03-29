<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class DesignResource
 * @package App\Http\Resources
 */
class DesignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'comments' => CommentResource::collection($this->comments),
            'user' => new UserResource($this->user),
            'title' => $this->title,
            'slug' => $this->slug,
            'team' => $this->team ? [
                'name' => $this->team->name,
                'slug' => $this->team->slug
            ] : null,
            'is_live' => $this->is_live,
            'likes_count' => $this->likes()->count(),
            'images' => $this->images,
            'description' => $this->description,
            'tag_list' => [
                'tags' => $this->tagArray,
                'normalized' => $this->tagArrayNormalized,
            ],
            'created_at_dates' => [
                'created_at_human' => $this->created_at->diffForHumans(),
                'created_at' => $this->created_at
            ],
            'updated_at_dates' => [
                'updated_at_human' => $this->updated_at->diffForHumans(),
                'updated_at' => $this->updated_at
            ],        ];
    }
}
