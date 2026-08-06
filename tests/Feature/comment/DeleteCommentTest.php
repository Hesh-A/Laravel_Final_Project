<?php

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Idea;

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
