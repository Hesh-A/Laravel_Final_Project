<?php

use App\Events\CollaboratorRequested;
use App\Models\IdeaCollaborator;

it('builds the collaborator requested broadcast payload', function () {
    $collaborator = IdeaCollaborator::factory()->create([
        'status' => 'pending',
    ])->fresh('user');

    $event = new CollaboratorRequested($collaborator);

    $channels = $event->broadcastOn();

    expect($event->collaborator->is($collaborator))->toBeTrue();
    expect($channels)->toHaveCount(1);
    expect($channels[0]->name)->toBe('idea.'.$collaborator->idea_id);
    expect($event->broadcastAs())->toBe('collaborator.requested');
    expect($event->broadcastWith())->toBe([
        'collaborator' => [
            'id' => $collaborator->id,
            'idea_id' => $collaborator->idea_id,
            'user_id' => $collaborator->user_id,
            'status' => 'pending',
            'approve_url' => route('collaborator.approve', $collaborator),
            'user' => [
                'id' => $collaborator->user->id,
                'name' => $collaborator->user->name,
            ],
        ],
    ]);
});
