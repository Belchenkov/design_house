<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Repositories\Contracts\IDesign;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\ForUser;
use App\Repositories\Eloquent\Criteria\IsLive;
use App\Repositories\Eloquent\Criteria\LatestFirst;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignController extends Controller
{
    protected IDesign $designs;

    public function __construct(IDesign $design)
    {
        $this->designs = $design;
    }

    public function index()
    {
        $designs = $this->designs->withCriteria([
            new LatestFirst(),
            new IsLive(),
            new ForUser(auth()->id()),
            new EagerLoad(['user', 'comments'])
        ])->all();

        return DesignResource::collection($designs);
    }

    public function findDesign(int $id): DesignResource
    {
        $design = $this->designs->find($id);

        return new DesignResource($design);
    }

    public function update(Request $request, $id): DesignResource
    {
        $design = $this->designs->find($id);

        $this->authorize('update', $design);

        $this->validate($request, [
            'title' => ['required', 'unique:designs,title,' . $id],
            'description' => ['required', 'string', 'min:20', 'max:140'],
            'tags' => ['required'],
            'team' => ['required_if:assign_to_team,true']
        ]);

        $design = $this->designs->update($id, [
            'team_id' => $request->team,
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => ! $design->upload_successful ? false : $request->is_live
        ]);

        // apply tags
        $this->designs->applyTags($design->id, $request->tags);

        return new DesignResource($design);
    }

    public function destroy($id)
    {
        $design = $this->designs->find($id);

        $this->authorize('delete', $design);

        // delete the files associated to the record
        $sizes = ['thumbnail', 'large', 'original'];

        foreach ($sizes as $size) {
            $disk = Storage::disk($design->disk);
            $image_path = 'uploads/designs/' . $size . '/' . $design->image;

            // check if the file exists in the db
            if ($disk->exists($image_path)) {
                $disk->delete($image_path);
            }
        }

        $this->designs->delete($id);

        return response()->json([
            'status' => true,
            'message' => 'Record deleted!'
        ]);
    }

    public function like(int $id): JsonResponse
    {
        $this->designs->like($id);

        return response()->json([
            'status' => true,
            'message' => 'Successful'
        ]);
    }

    public function checkIfUserHasLiked(int $design_id): JsonResponse
    {
        $isLiked = $this->designs->isLikedByUser($design_id);
        return response()->json([
            'status' => true,
            'liked' => $isLiked
        ]);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function search(Request $request): AnonymousResourceCollection
    {
        $designs = $this->designs->search($request);
        return DesignResource::collection($designs);
    }

    /**
     * @param string $slug
     * @return DesignResource
     */
    public function findBySlug(string $slug): DesignResource
    {
        $design = $this->designs
            ->withCriteria([new IsLive()])
            ->findWhereFirst('slug', $slug);
        return new DesignResource($design);
    }

    /**
     * @param int $team_id
     * @return AnonymousResourceCollection
     */
    public function getForTeam(int $team_id): AnonymousResourceCollection
    {
        $designs = $this->designs
            ->withCriteria([new IsLive()])
            ->findWhere('team_id', $team_id);
        return DesignResource::collection($designs);
    }

    /**
     * @param int $user_id
     * @return AnonymousResourceCollection
     */
    public function getForUser(int $user_id): AnonymousResourceCollection
    {
        $designs = $this->designs
            ->withCriteria([new IsLive()])
            ->findWhere('user_id', $user_id);
        return DesignResource::collection($designs);
    }

    /**
     * @param int $id
     * @return DesignResource
     */
    public function userOwnsDesign(int $id): DesignResource
    {
        $design = $this->designs
            ->withCriteria(
                [ new ForUser(auth()->id()) ]
            )
            ->findWhereFirst('id', $id);

        return new DesignResource($design);
    }
}
