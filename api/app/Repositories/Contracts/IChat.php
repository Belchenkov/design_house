<?php


namespace App\Repositories\Contracts;


use Illuminate\Database\Eloquent\Collection;

interface IChat
{
    public function createParticipants(int $chat_id, array $data): void;
    public function getUserChats(): Collection;
}
