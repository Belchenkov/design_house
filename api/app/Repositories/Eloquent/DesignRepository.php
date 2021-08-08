<?php


namespace App\Repositories\Eloquent;


use App\Models\Comment;
use App\Models\Design;
use App\Repositories\Contracts\IDesign;
use Illuminate\Http\Request;

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

    /**
     * @param int $id
     * @return bool
     */
    public function isLikedByUser(int $id): bool
    {
        $design = $this->model->findOrFail($id);
        return $design->isLikedByUser(auth()->id());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        $query = (new $this->model)->newQuery();
        $query->where('is_live', true);

        // return only designs with comments
        if ($request->has('has_comments')) {
            $query->has('comments');
        }
        // return only designs assigned to teams
        if ($request->has('has_team')) {
            $query->has('team');
        }

        // search title and description for provided string
        if ($request->has('q')) {
            $query->where(function ($q) use ($request) {
                $q
                    ->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        // order the query by likes or latest first
        if ((string)$request->orderBy === 'likes') {
            $query
                ->withCount('likes')
                ->orderByDesc('likes_count');
        } else {
            $query->latest();
        }

        return $query->get();
    }
}
