<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('updates an idea for an authenticated user', function () {
    $user = User::factory()->create();

    $idea = $user->ideas()->create([
        'title' => 'My first idea',
        'description' => 'Feature test description',
        'status' => 'pending',
        'links' => ['https://example.com'],
    ]);

    $idea->steps()->createMany([
        ['description' => 'Do first step', 'is_completed' => false],
    ]);

    $payload = [
        'title' => 'Updated idea',
        'description' => 'Updated description',
        'status' => 'completed',
        'links' => ['https://updated-example.com'],
        'steps' => [
            ['description' => 'Do updated step', 'is_completed' => true],
        ],
    ];

    // uses the action handle to update the idea
    $response = $this
        ->actingAs($user)
        ->patch(route('idea.update', $idea), $payload);

    $response->assertRedirect(route('idea.show', $idea));
    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('ideas', [
        'id' => $idea->id,
        'title' => 'Updated idea',
        'description' => 'Updated description',
        'status' => 'completed',
        'user_id' => $user->id,
    ]);

    $this->assertDatabaseHas('steps', [
        'idea_id' => $idea->id,
        'description' => 'Do updated step',
        'is_completed' => true,
    ]);

    $this->assertDatabaseMissing('steps', [
        'idea_id' => $idea->id,
        'description' => 'Do first step',
    ]);
});
