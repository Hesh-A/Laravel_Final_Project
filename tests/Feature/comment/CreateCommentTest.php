<?php

use App\Events\CommentCreated;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

it('stores a comment for an authenticated user', function () {
    Event::fake([CommentCreated::class]);

    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('comment.store', $idea), [
            'content' => 'This is a feature test comment.',
        ]);

    $response->assertRedirect(route('idea.show', $idea));
    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('comments', [
        'idea_id' => $idea->id,
        'user_id' => $user->id,
        'content' => 'This is a feature test comment.',
    ]);

    $comment = Comment::query()->where('idea_id', $idea->id)->first();

    expect($comment)->not->toBeNull();

    Event::assertDispatched(CommentCreated::class, function (CommentCreated $event) use ($comment) {
        return $event->comment->is($comment);
    });
});

it('validates that comment content is required', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('idea.show', $idea))
        ->post(route('comment.store', $idea), [
            'content' => '',
        ]);

    $response->assertRedirect(route('idea.show', $idea));
    $response->assertSessionHasErrors(['content']);

    expect(Comment::query()->count())->toBe(0);
});
