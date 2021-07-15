<?php


namespace App\Repositories\Contracts;


use App\Models\Comment;

interface IDesign
{
    public function applyTags(int $id, array $data): void;
    public function addComment(int $design_id, array $data): Comment;
}
