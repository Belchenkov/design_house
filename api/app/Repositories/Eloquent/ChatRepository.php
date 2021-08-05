<?php


namespace App\Repositories\Eloquent;


use App\Models\Chat;
use App\Repositories\Contracts\IChat;
use Illuminate\Database\Eloquent\Collection;

class ChatRepository extends BaseRepository implements IChat
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Chat::class;
    }

    /**
     * @param int $chat_id
     * @param array $data
     */
    public function createParticipants(int $chat_id, array $data): void
    {
        $chat = $this->find($chat_id);
        $chat->participants()->sync($data);
    }

    /**
     * @return Collection
     */
    public function getUserChats(): Collection
    {
        return auth()->user()->chats()
            ->with(['messages', 'participants'])
            ->get();
    }
}
