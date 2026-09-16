<?php

namespace App\Events;

use App\Models\IdeaCollaborator;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CollaboratorApproved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public IdeaCollaborator $collaborator) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('idea.'.$this->collaborator->idea_id),
            new PrivateChannel('App.Models.User.'.$this->collaborator->user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'collaborator.approved';
    }

    public function broadcastWith(): array
    {
        return [
            'collaborator' => [
                'id' => $this->collaborator->id,
                'idea_id' => $this->collaborator->idea_id,
                'user_id' => $this->collaborator->user_id,
                'status' => $this->collaborator->status->value,
            ],
        ];
    }
}
