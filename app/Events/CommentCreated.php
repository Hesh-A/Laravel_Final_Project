<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Comment $comment)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('idea.'.$this->comment->idea_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'comment.created';
    }

    public function broadcastWith(): array
    {
        $user = $this->comment->user;

        return [
            'comment' => [
                'id' => $this->comment->id,
                'content' => $this->comment->content,
                'idea_id' => $this->comment->idea_id,
                'delete_url' => route('comment.destroy', $this->comment),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
            ],
        ];
    }
}
