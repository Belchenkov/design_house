<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Repositories\Contracts\IChat;
use App\Repositories\Contracts\IMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatController extends Controller
{
    protected IChat $chats;
    protected IMessage $messages;

    public function __construct(IChat $chats, IMessage $messages)
    {
        $this->chats = $chats;
        $this->messages = $messages;
    }

    /**
     * @param Request $request
     * @return MessageResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendMessage(Request $request): MessageResource
    {
        $this->validate($request, [
            'recipient' => ['required', 'integer'],
            'body' => ['required'],
        ]);

        $recipient = $request->recipient;
        $user = auth()->user();
        $body = $request->body;

        // check if there is an existing chat between the auth user and the recipient
        $chat = $user->getChatWithUser($recipient);

        if (! $chat) {
            $chat = $this->chats->create([]);
            $this->chats->createParticipants($chat->id, [$user->id, $recipient]);
        }

        // add the message to the chat
        $message = $this->messages->create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'body' => $body,
            'last_read' => null
        ]);

        return new MessageResource($message);
    }

    public function getUserChats(): AnonymousResourceCollection
    {
        $chats = $this->chats->getUserChats();
        return ChatResource::collection($chats);
    }
}
