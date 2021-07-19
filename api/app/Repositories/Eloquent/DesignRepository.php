<?php


namespace App\Repositories\Eloquent;


use App\Models\Comment;
use App\Models\Design;
use App\Repositories\Contracts\IDesign;

class DesignRepository extends BaseRepository implements IDesign
{
    public function model(): string
    {
        return Design::class;
    }

    public function applyTags(int $id, array $data): void
    {
        $design = $this->find($id);
        $design->retag($data);
    }

    public function addComment(int $design_id, array $data): Comment
    {
        // get the design for which we want to create a comment
        $design = $this->find($design_id);

        // create the comment for the design
        return $design->comments()->create($data);
    }

    public function like(int $id): void
    {
        $designs = $this->model->findOrFail($id);

        if ($designs->isLikedByUser(auth()->id())) {
            $designs->unlike();
        } else {
            $designs->like();
        }
    }

    public function isLikedByUser(int $id): bool
    {
        $design = $this->model->findOrFail($id);
        return $design->isLikedByUser(auth()->id());
    }
}
