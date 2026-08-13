<?php

use App\Events\CollaboratorApproved;
use App\Models\IdeaCollaborator;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

it('builds the collaborator approved broadcast payload', function () {
    $collaborator = IdeaCollaborator::factory()->create([
        'status' => 'approved',
    ]);

    $event = new CollaboratorApproved($collaborator);

    $channels = $event->broadcastOn();

    expect($event->collaborator->is($collaborator))->toBeTrue();
    expect($channels)->toHaveCount(2)
        ->and($channels[0])->toBeInstanceOf(Channel::class)
        ->and($channels[0]->name)->toBe('idea.'.$collaborator->idea_id)
        ->and($channels[1])->toBeInstanceOf(PrivateChannel::class)
        ->and($channels[1]->name)->toBe('private-App.Models.User.'.$collaborator->user_id);
    expect($event->broadcastAs())->toBe('collaborator.approved');
    expect($event->broadcastWith())->toBe([
        'collaborator' => [
            'id' => $collaborator->id,
            'idea_id' => $collaborator->idea_id,
            'user_id' => $collaborator->user_id,
            'status' => 'approved',
        ],
    ]);
});
