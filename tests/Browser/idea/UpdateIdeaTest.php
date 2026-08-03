<?php

use App\Models\Idea;
use App\Models\User;

it('edits an idea', function () {
    // when a user is authenticated
    $user = User::factory()->create();

    $idea = Idea::factory()->for($user)->create([
        'title' => 'Build something',
        'description' => 'Create happy path.',
        'status' => 'pending',
        'links' => [],
    ]);

    $this->actingAs($user);

    // the idea update works
    visit(route('idea.show', $idea))
        ->click('@edit-idea-button')
        ->fill('title', 'Build something else')
        ->click('@status-button-pending')
        ->fill('description', 'Create happy path 2.0.')
        ->fill('@new-step', 'Step 1.1')
        ->click('@add-new-step-button')
        ->fill('@new-step', 'Step 2.2')
        ->click('@add-new-step-button')
        ->fill('@new-link', 'https://www.example.com')
        ->click('@add-new-link-button')
        ->fill('@new-link', 'https://www.laravel.com')
        ->click('@add-new-link-button')
        ->click('@update-idea-button')
        ->assertPathIs('/ideas/'.$idea->id);

    $idea = $idea->fresh();

    expect($idea)->toMatchArray([
        'title' => 'Build something else',
        'description' => 'Create happy path 2.0.',
        'status' => 'pending',
    ]);

    expect($idea->links->toArray())->toBe([
        'https://www.example.com',
        'https://www.laravel.com',
    ]);

});
