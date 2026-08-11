<?php

use App\Models\Comment;
use App\Models\User;
use App\Policies\CommentPolicy;

it('allows viewing comments', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create();

    $policy = new CommentPolicy;

    expect($policy->canView($user, $comment))->toBeTrue();
});
