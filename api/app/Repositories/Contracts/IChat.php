<?php


namespace App\Repositories\Contracts;


interface IChat
{
    public function createParticipants(int $chat_id, array $data): void;
}
