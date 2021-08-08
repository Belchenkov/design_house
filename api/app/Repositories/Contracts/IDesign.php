<?php


namespace App\Repositories\Contracts;


use App\Models\Comment;
use Illuminate\Http\Request;

interface IDesign
{
    public function applyTags(int $id, array $data): void;
    public function addComment(int $design_id, array $data): Comment;
    public function like(int $id): void;
    public function isLikedByUser(int $id): bool;
    public function search(Request $request);
}
