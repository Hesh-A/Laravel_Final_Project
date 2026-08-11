<?php

use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('deletes a comment for an authorized user', function () {

    $user = User::factory()->create();
    $idea = Idea::factory()->create();
    $comment = Comment::factory()->for($user)->for($idea)->create();

    $response = $this

        ->actingAs($user)
        ->delete(route('comment.destroy', $comment));

    $response->assertStatus(302);

    expect(Comment::find($comment->id))->toBeNull();
});

it('does not delete a comment for a non owner', function () {

    $owner = User::factory()->create();
    $nonOwner = User::factory()->create();
    $idea = Idea::factory()->create();
    $comment = Comment::factory()->for($owner)->for($idea)->create();

    $response = $this
        ->actingAs($nonOwner)
        ->delete(route('comment.destroy', $comment));

    $response->assertForbidden();

    expect(Comment::find($comment->id))->not->toBeNull();
});
