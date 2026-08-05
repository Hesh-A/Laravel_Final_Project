<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Idea;
use App\CollaborationStatus;

use Illuminate\Support\Facades\Gate;

use App\Models\IdeaCollaborator;

class IdeaCollaborationController extends Controller
{
    public function requestCollaboration(Request $request, Idea $idea)
    {

        $user = Auth::user();

        if ($idea->collaborators()->where('user_id', $user->id)->exists()) {
            return redirect()->route('idea.show', $idea)->with('error', 'You have already requested or been approved for this idea.');
        }

        $idea->collaborators()->create([
            'user_id' => $user->id,
            'status' => CollaborationStatus::PENDING,
        ]);

        return redirect()->route('idea.show', $idea)->with('success', 'Collaboration request sent successfully!');
    }

    public function approve(Request $request, IdeaCollaborator $collaborator)
    {
        $idea = $collaborator->idea;
        Gate::authorize('canModify', $idea);

        $collaborator->update([
            'status' => CollaborationStatus::APPROVED,
        ]);

        return redirect()->route('idea.show', $idea)->with('success', 'Collaboration request approved successfully!');
    }
}
