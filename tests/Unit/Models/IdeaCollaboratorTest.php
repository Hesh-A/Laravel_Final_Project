<?php

use App\Models\Idea;
use App\Models\IdeaCollaborator;
use App\Models\User;

it('creates an idea collaborator', function () {

    $ideaCollaborator = IdeaCollaborator::factory()->create();
    expect($ideaCollaborator)->toBeInstanceOf(IdeaCollaborator::class);

    expect($ideaCollaborator->id)->not->toBeNull();
});

it('belongs to a user', function () {

    $ideaCollaborator = IdeaCollaborator::factory()->create();
    expect($ideaCollaborator->user)->toBeInstanceOf(User::class);
});

it('belongs to an idea', function () {
    $ideaCollaborator = IdeaCollaborator::factory()->create();
    expect($ideaCollaborator->idea)->toBeInstanceOf(Idea::class);
});
