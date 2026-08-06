<?php

use App\Models\Idea;
use App\Models\User;

it('creates a user', function () {

    $user = User::factory()->create();
    expect($user)->toBeInstanceOf(User::class);

    expect($user->id)->not->toBeNull();
});

it('has many ideas', function () {

    $user = User::factory()->create();
    $user->ideas()->create([
        'title' => 'Test Idea',
        'description' => 'This is a test idea.',
        'links' => ['https://example.com'],
    ]);
    expect($user->ideas)->toHaveCount(1);
    expect($user->ideas->first()->title)->toBe('Test Idea');
});

it('has many comments', function () {

    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();

    $user->comments()->create([
        'idea_id' => $idea->id,
        'content' => 'This is a test comment.',
    ]);

    expect($user->comments)->toHaveCount(1);
    expect($user->comments->first()->content)->toBe('This is a test comment.');
});

it('has many idea collaborations', function () {
    $user = User::factory()->create();

    $ideas = Idea::factory()->count(2)->for($user)->create();

    $user->collaborations()->createMany([
        ['idea_id' => $ideas[0]->id],
        ['idea_id' => $ideas[1]->id],
    ]);

    expect($user->collaborations)->toHaveCount(2);
});
