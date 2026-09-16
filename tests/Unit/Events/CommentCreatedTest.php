<?php

use App\Events\CommentCreated;
use App\Models\Comment;

it('triggers a broadcast event when a comment is created', function () {

    $comment = Comment::factory()->create([
        'content' => 'Realtime comment',
    ]);

    $event = new CommentCreated($comment->fresh('user'));

    $channels = $event->broadcastOn();

    expect($event->comment->is($comment))->toBeTrue();
    expect($channels)->toHaveCount(1);
    expect($channels[0]->name)->toBe('idea.'.$comment->idea_id);
    expect($event->broadcastAs())->toBe('comment.created');
    expect($event->broadcastWith())->toBe([
        'comment' => [
            'id' => $comment->id,
            'content' => 'Realtime comment',
            'idea_id' => $comment->idea_id,
            'delete_url' => route('comment.destroy', $comment),
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
            ],
        ],
    ]);

});
