<?php

use App\Models\Idea;
use App\Models\User;
use App\Models\Step;


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




