<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\ChatUsers;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $chats = auth()
            ->user()
            ->getChatsWithUsersAttribute();
        $chats = $chats
            ->with([
            'chat_users.owner:id,name,avatar',
            'chat_users.user:id,name,avatar',
//            'messages' => function($q) {
//                    $q->whereRaw(('chat_messages.id =
//                    (select MAX(chat_messages.id)
//                    FROM chat_messages
//                    WHERE chat_messages.chat_id = chats.id'
//                    ));
//                }
            ])
            ->withCount(['messages' => function ($messages) {
                return $messages->where('read', 0)->where('chat_users.user_id', '!=', auth()->id());
            }])
            ->selectRaw(
                'chat_messages.created_at as last_message_created_at,
                chat_messages.text as last_message_text,
                chat_messages.id as last_message_id,
                chat_messages.user_id as last_message_user_id,
                chat_messages.read as last_message_read ')
            ->leftJoin('chat_messages', 'chat_messages.chat_id', 'chats.id')
            ->whereRaw('chat_messages.id = (SELECT max(id) FROM chat_messages WHERE chat_messages.chat_id = chats.id )')
            ->orderByDesc('last_message_created_at')
            ->distinct()
            ->get()
        ;
        $chats->map(function ($chat) {
            $chat->talker =
                $chat->chat_users->user->id == auth()->id()
                ? $chat->chat_users->owner
                : $chat->chat_users->user;
            $chat->setHidden(['chat_users']);
            $chat->messages = [
                'id' => $chat->last_message_id,
                'user_id' => $chat->last_message_user_id,
                'text' => $chat->last_message_text,
                'read' => $chat->last_message_read,
                'created_at' => $chat->last_message_created_at,
            ];
            return $chat;
        });
        return $this->Result(200, $chats);
    }

    public function show($chat)
    {
        $chat = Chat::query()
            ->with(['chat_users'])
            ->where('chats.id', $chat)
            ->first();
        $messages_query = ChatMessage::query()
            ->where('chat_id', $chat->id)
            ->orderByDesc('id')
        ;
        ChatMessage::query()
            ->where('user_id', '!=', auth()->id())
            ->where('chat_id', $chat->id)
            ->update(['read' => 1]);
        $talker =
            $chat->chat_users->user_id == auth()->id()
                ? $chat->chat_users->owner()
                : $chat->chat_users->user();
        $talker = $talker->select(['id', 'name', 'avatar', 'updated_at'])->first();
        $talker->user_id = $talker->id;
        $chat->setHidden(['chat_users']);

        return $this->Result(200, [
            'chat' => $chat,
            'talker' => $talker,
            'messages' => $messages_query->get(),
        ]);
    }
}
