<?php


namespace App\Repositories\Contracts;


interface IDesign
{
    public function applyTags(int $id, array $data): void;
}
