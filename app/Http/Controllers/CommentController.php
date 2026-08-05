<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\CreateComment;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Idea;
use Illuminate\Support\Facades\Gate;
use App\Models\Comment;

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

        $comment->delete();

        return redirect()->route('idea.show', $comment->idea)->with('success', 'Comment deleted successfully!');
    }

    
}
