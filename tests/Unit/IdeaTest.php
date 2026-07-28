<?php

use App\Models\Idea;
use App\Models\User;
use App\Models\Step;


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


