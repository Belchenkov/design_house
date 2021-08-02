<?php


namespace App\Repositories\Eloquent;


use App\Models\Chat;
use App\Repositories\Contracts\IChat;

class ChatRepository extends BaseRepository implements IChat
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Chat::class;
    }

    public function createParticipants(int $chat_id, array $data): void
    {
        $chat = $this->find($chat_id);
        $chat->participants()->sync($data);
    }
}
