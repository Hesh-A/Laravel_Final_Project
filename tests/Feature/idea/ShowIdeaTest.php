<?php

use App\Models\Idea;
use App\Models\User;

it('displays an idea for an authenticated user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $idea = Idea::factory()->create(['user_id' => $user->id]);

    $response = $this->get(route('idea.show', $idea));

    $response->assertStatus(200);
    $response->assertSee($idea->title);
    $response->assertSee($idea->description);
});
