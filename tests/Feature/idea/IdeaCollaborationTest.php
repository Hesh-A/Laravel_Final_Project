<?php

use App\Models\Idea;
use App\Models\IdeaCollaborator;
use App\Models\User;


it('lets a non collaborator request collaboration', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $this->actingAs($user);

    $response = $this->post(route('ideas.collaboration.request', $idea));

    $response->assertRedirect(route('idea.show', $idea));
    $response->assertSessionHas('success', 'Collaboration request sent successfully!');

    $this->assertDatabaseHas('idea_collaborators', [
        'idea_id' => $idea->id,
        'user_id' => $user->id,
        'status' => 'pending',
    ]);
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

it('lets the owner approve collaborations', function(){

  $owner = User::factory()->create();
  $idea = Idea::factory()->create(['user_id' => $owner->id]);
  $collaborator = User::factory()->create();

  IdeaCollaborator::factory()->create([
      'idea_id' => $idea->id,
      'user_id' => $collaborator->id,
      'status' => 'pending',
  ]);

    $this->actingAs($owner);


    $response = $this->patch(route('collaborator.approve', [
        'collaborator' => IdeaCollaborator::where('idea_id', $idea->id)
            ->where('user_id', $collaborator->id)
            ->firstOrFail(),
    ]));

    $response->assertRedirect(route('idea.show', $idea));
    $response->assertSessionHas('success', 'Collaboration request approved successfully!');

    $this->assertDatabaseHas('idea_collaborators', [
        'idea_id' => $idea->id,
        'user_id' => $collaborator->id,
        'status' => 'approved',
    ]);


});