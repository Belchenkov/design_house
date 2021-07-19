<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Repositories\Contracts\IComment;
use App\Repositories\Contracts\IDesign;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    protected IComment $comments;
    protected IDesign $designs;

    public function __construct(IComment $comments, IDesign $designs)
    {
        $this->comments = $comments;
        $this->designs = $designs;
    }

    public function store(Request $request, int $design_id)
    {
        $this->validate($request, [
            'body' => ['required']
        ]);

        $comment = $this->designs->addComment($design_id, [
            'body' => $request->body,
            'user_id' => auth()->id(),
        ]);

        return new CommentResource($comment);
    }

    public function update(Request $request, int $id)
    {
        $comment = $this->comments->find($id);
        $this->authorize('update', $comment);

        $this->validate($request, [
           'body' => ['required']
        ]);

        $comment = $this->comments->update($id, [
            'body' => $request->body
        ]);


        return new CommentResource($comment);
    }

    public function destroy(int $id)
    {
        $comment = $this->comments->find($id);
        $this->authorize('delete', $comment);

        $this->comments->delete($id);

        return response()->json([
            'message' => 'Comment Deleted!'
        ], Response::HTTP_NO_CONTENT);
    }
}
