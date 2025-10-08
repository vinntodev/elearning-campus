<?php

namespace App\Events;

use App\Models\Reply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReplyCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reply;

    public function __construct(Reply $reply)
    {
        $this->reply = $reply->load('user');
    }

    public function broadcastOn()
    {
        return new Channel('discussion.' . $this->reply->discussion_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->reply->id,
            'discussion_id' => $this->reply->discussion_id,
            'content' => $this->reply->content,
            'user' => [
                'id' => $this->reply->user->id,
                'name' => $this->reply->user->name,
                'role' => $this->reply->user->role,
            ],
            'created_at' => $this->reply->created_at,
        ];
    }
}