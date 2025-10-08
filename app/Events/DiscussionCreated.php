<?php

namespace App\Events;

use App\Models\Discussion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DiscussionCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $discussion;

    public function __construct(Discussion $discussion)
    {
        $this->discussion = $discussion->load('user');
    }

    public function broadcastOn()
    {
        return new Channel('course.' . $this->discussion->course_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->discussion->id,
            'course_id' => $this->discussion->course_id,
            'content' => $this->discussion->content,
            'user' => [
                'id' => $this->discussion->user->id,
                'name' => $this->discussion->user->name,
                'role' => $this->discussion->user->role,
            ],
            'created_at' => $this->discussion->created_at,
        ];
    }
}