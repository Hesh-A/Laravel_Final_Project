<?php

namespace App\Http\Controllers;

use App\Actions\CreateComment;
use App\Events\CommentDeleted;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Idea;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Idea $idea, CreateComment $action)
    {
        $action->handle(array_merge($request->safe()->all(), ['idea_id' => $idea->id]));

        return redirect()->route('idea.show', $idea)->with('success', 'Comment created successfully!');
    }

    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $commentId = $comment->id;
        $ideaId = $comment->idea_id;
        $idea = $comment->idea;

        Comment::query()->whereKey($commentId)->delete();

        CommentDeleted::dispatch($commentId, $ideaId);

        return redirect()->route('idea.show', $idea)->with('success', 'Comment deleted successfully!');
    }
}
