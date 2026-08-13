<?php

use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;

it('creates a comment', function () {

    $comment = Comment::factory()->create();
    expect($comment)->toBeInstanceOf(Comment::class);

    expect($comment->id)->not->toBeNull();
});

it('belongs to a user', function () {

    $comment = Comment::factory()->create();
    expect($comment->user)->toBeInstanceOf(User::class);
});

it('belongs to an idea', function () {
    $comment = Comment::factory()->create();
    expect($comment->idea)->toBeInstanceOf(Idea::class);
});
