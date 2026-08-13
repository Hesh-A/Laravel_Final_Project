<?php

use App\Models\Idea;
use App\Models\User;

it('creates an idea', function () {

    $idea = Idea::factory()->create();
    expect($idea)->toBeInstanceOf(Idea::class);

    expect($idea->id)->not->toBeNull();
});

it('belongs to a user', function () {

    $idea = Idea::factory()->create();
    expect($idea->user)->toBeInstanceOf(User::class);
});

it('has many steps', function () {
    $idea = Idea::factory()->create();

    $idea->steps()->create([
        'description' => 'Step 1',

    ]);

    expect($idea->fresh()->steps)->toHaveCount(1);

});

it('has many comments', function () {

    $idea = Idea::factory()->create();

    $users = User::factory()->count(6)->create();

    $idea->comments()->createMany([
        ['user_id' => $users[0]->id, 'content' => 'This is a test comment.'],
        ['user_id' => $users[1]->id, 'content' => 'This is another test comment.'],
        ['user_id' => $users[2]->id, 'content' => 'This is yet another test comment.'],
        ['user_id' => $users[3]->id, 'content' => 'This is a fourth test comment.'],
        ['user_id' => $users[4]->id, 'content' => 'This is a fifth test comment.'],
        ['user_id' => $users[5]->id, 'content' => 'This is a sixth test comment.'],
    ]);

    expect($idea->comments)->toHaveCount(6);
    expect($idea->comments->first()->content)->toBe('This is a test comment.');
});

it('has many collaborators', function () {
    $idea = Idea::factory()->create();

    $users = User::factory()->count(3)->create();

    $idea->collaborators()->createMany([
        ['user_id' => $users[0]->id],
        ['user_id' => $users[1]->id],
        ['user_id' => $users[2]->id],
    ]);

    expect($idea->collaborators)->toHaveCount(3);
});
