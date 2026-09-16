<?php

use App\Events\CollaboratorApproved;
use App\Events\CollaboratorRequested;
use App\Models\Idea;
use App\Models\IdeaCollaborator;
use App\Models\User;
use Illuminate\Support\Facades\Event;

it('lets a non collaborator request collaboration', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    Event::fake([CollaboratorRequested::class]);

    $this->actingAs($user);

    $response = $this->post(route('ideas.collaboration.request', $idea));

    $response->assertRedirect(route('idea.show', $idea));
    $response->assertSessionHas('success', 'Collaboration request sent successfully!');

    $this->assertDatabaseHas('idea_collaborators', [
        'idea_id' => $idea->id,
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    $collaborator = IdeaCollaborator::query()
        ->where('idea_id', $idea->id)
        ->where('user_id', $user->id)
        ->firstOrFail();

    Event::assertDispatched(CollaboratorRequested::class, function (CollaboratorRequested $event) use ($collaborator) {
        return $event->collaborator->is($collaborator)
            && $event->collaborator->relationLoaded('user');
    });
});

it('prevents duplicate collaboration requests', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $this->actingAs($user);

    $firstResponse = $this->post(route('ideas.collaboration.request', $idea));

    $firstResponse->assertRedirect(route('idea.show', $idea));
    $firstResponse->assertSessionHas('success', 'Collaboration request sent successfully!');

    $secondResponse = $this->post(route('ideas.collaboration.request', $idea));

    $secondResponse->assertRedirect(route('idea.show', $idea));
    $secondResponse->assertSessionHas('error', 'You have already requested or been approved for this idea.');

    $this->assertDatabaseCount('idea_collaborators', 1);
});

it('lets the owner approve collaborations', function () {
    $owner = User::factory()->create();
    $idea = Idea::factory()->create(['user_id' => $owner->id]);
    $collaborator = User::factory()->create();
    $pendingCollaborator = IdeaCollaborator::factory()->create([
        'idea_id' => $idea->id,
        'user_id' => $collaborator->id,
        'status' => 'pending',
    ]);

    Event::fake([CollaboratorApproved::class]);

    $this->actingAs($owner);

    $response = $this->patch(route('collaborator.approve', [
        'collaborator' => $pendingCollaborator,
    ]));

    $response->assertRedirect(route('idea.show', $idea));
    $response->assertSessionHas('success', 'Collaboration request approved successfully!');

    $this->assertDatabaseHas('idea_collaborators', [
        'idea_id' => $idea->id,
        'user_id' => $collaborator->id,
        'status' => 'approved',
    ]);

    Event::assertDispatched(CollaboratorApproved::class, function (CollaboratorApproved $event) use ($pendingCollaborator) {
        return $event->collaborator->id === $pendingCollaborator->id
            && $event->collaborator->status->value === 'approved';
    });

});

it('prevents non owners from approving collaborations', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $idea = Idea::factory()->create(['user_id' => $owner->id]);
    $pendingCollaborator = IdeaCollaborator::factory()->create([
        'idea_id' => $idea->id,
        'user_id' => User::factory()->create()->id,
        'status' => 'pending',
    ]);

    Event::fake([CollaboratorApproved::class]);

    $response = $this
        ->actingAs($intruder)
        ->patch(route('collaborator.approve', $pendingCollaborator));

    $response->assertForbidden();
    $response->assertSee('You are not authorized to do that.');

    $this->assertDatabaseHas('idea_collaborators', [
        'id' => $pendingCollaborator->id,
        'status' => 'pending',
    ]);

    Event::assertNotDispatched(CollaboratorApproved::class);

});
