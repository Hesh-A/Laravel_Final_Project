<?php

use App\Events\CommentDeleted;
use App\Models\Comment;

it('triggers a broadcast event when a comment is deleted', function () {

    $comment = Comment::factory()->create([
        'content' => 'Realtime comment',
    ]);

    $event = new CommentDeleted($comment->id, $comment->idea_id);

    $channels = $event->broadcastOn();

    expect($event->commentId)->toBe($comment->id);
    expect($channels)->toHaveCount(1);
    expect($channels[0]->name)->toBe('idea.'.$comment->idea_id);
    expect($event->broadcastAs())->toBe('comment.deleted');
    expect($event->broadcastWith())->toBe([
        'comment' => [
            'id' => $comment->id,
            'idea_id' => $comment->idea_id,
        ],
    ]);

});
