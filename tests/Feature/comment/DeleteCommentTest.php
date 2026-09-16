<?php

use App\Events\CommentDeleted;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

it('deletes a comment for an authorized user', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();
    $comment = Comment::factory()->for($user)->for($idea)->create();

    Event::fake([CommentDeleted::class]);

    $response = $this
        ->actingAs($user)
        ->delete(route('comment.destroy', $comment));

    $response->assertRedirect(route('idea.show', $idea));

    expect(Comment::find($comment->id))->toBeNull();

    Event::assertDispatched(CommentDeleted::class, function (CommentDeleted $event) use ($comment) {
        return $event->commentId === $comment->id
            && $event->ideaId === $comment->idea_id;
    });
});

it('lets the idea owner delete another users comment', function () {
    $ideaOwner = User::factory()->create();
    $commentAuthor = User::factory()->create();
    $idea = Idea::factory()->for($ideaOwner)->create();
    $comment = Comment::factory()->for($commentAuthor)->for($idea)->create();

    Event::fake([CommentDeleted::class]);

    $response = $this
        ->actingAs($ideaOwner)
        ->delete(route('comment.destroy', $comment));

    $response->assertRedirect(route('idea.show', $idea));

    expect(Comment::find($comment->id))->toBeNull();

    Event::assertDispatched(CommentDeleted::class, function (CommentDeleted $event) use ($comment) {
        return $event->commentId === $comment->id
            && $event->ideaId === $comment->idea_id;
    });
});

it('does not delete a comment for a non owner', function () {
    $owner = User::factory()->create();
    $nonOwner = User::factory()->create();
    $idea = Idea::factory()->create();
    $comment = Comment::factory()->for($owner)->for($idea)->create();

    Event::fake([CommentDeleted::class]);

    $response = $this
        ->actingAs($nonOwner)
        ->delete(route('comment.destroy', $comment));

    $response->assertForbidden();

    expect(Comment::find($comment->id))->not->toBeNull();

    Event::assertNotDispatched(CommentDeleted::class);
});
