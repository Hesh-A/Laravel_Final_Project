<?php

namespace App\Http\Controllers;

use App\CollaborationStatus;
use App\Events\CollaboratorApproved;
use App\Events\CollaboratorRequested;
use App\Models\Idea;
use App\Models\IdeaCollaborator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class IdeaCollaborationController extends Controller
{
    public function requestCollaboration(Request $request, Idea $idea)
    {
        $user = Auth::user();

        if ($idea->collaborators()->where('user_id', $user->id)->exists()) {
            return redirect()->route('idea.show', $idea)->with('error', 'You have already requested or been approved for this idea.');
        }

        $collaborator = $idea->collaborators()->create([
            'user_id' => $user->id,
            'status' => CollaborationStatus::PENDING,
        ]);

        $collaborator->load('user');

        CollaboratorRequested::dispatch($collaborator);

        return redirect()->route('idea.show', $idea)->with('success', 'Collaboration request sent successfully!');
    }

    public function approve(Request $request, IdeaCollaborator $collaborator)
    {
        $idea = $collaborator->idea;
        Gate::authorize('canModify', $idea);

        $collaborator->update([
            'status' => CollaborationStatus::APPROVED,
        ]);

        $collaborator->refresh();

        CollaboratorApproved::dispatch($collaborator);

        return redirect()->route('idea.show', $idea)->with('success', 'Collaboration request approved successfully!');
    }
}
