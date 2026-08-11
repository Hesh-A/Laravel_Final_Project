<?php

use App\Models\Idea;
use App\Models\Step;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('toggles a step for the idea owner', function () {
    $owner = User::factory()->create();

    $idea = Idea::factory()->for($owner)->create();
    $step = Step::factory()->for($idea)->create([
        'is_completed' => false,
    ]);

    $response = $this
        ->actingAs($owner)
        ->from(route('idea.show', $idea))
        ->patch(route('step.update', $step));

    $response->assertRedirect(route('idea.show', $idea));
    $response->assertSessionHasNoErrors();

    expect((bool) $step->fresh()->is_completed)->toBeTrue();

    $response = $this
        ->actingAs($owner)
        ->from(route('idea.show', $idea))
        ->patch(route('step.update', $step));

    $response->assertRedirect(route('idea.show', $idea));
    $response->assertSessionHasNoErrors();

    expect((bool) $step->fresh()->is_completed)->toBeFalse();
});