<?php

use App\Models\Idea;
use App\Models\User;

it('requires authentication', function () {
    $idea = Idea::factory()->create();

    visit('/ideas/'.$idea->id)
        ->assertPathIs('/login');
});

it('requires authorisation', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $idea = Idea::factory()->create();

    visit('/ideas/'.$idea->id)
        ->assertPathIs('/ideas/'.$idea->id)
        ->assertSee('403');
});
