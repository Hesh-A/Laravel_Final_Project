<?php

use App\Models\Idea;
use App\Models\User;

it('deletes an idea', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $idea = Idea::factory()->create(['user_id' => $user->id]);

    $response = $this->delete(route('idea.destroy', $idea));

    $response->assertRedirect(route('idea.index'));
    $response->assertSessionHasNoErrors();

    expect(Idea::find($idea->id))->toBeNull();
});
