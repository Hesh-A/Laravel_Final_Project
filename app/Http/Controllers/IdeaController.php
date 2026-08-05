<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateIdea;
use App\Actions\UpdateIdea;
use App\Http\Requests\IdeaRequest;
use App\Actions\ListIdea;
use App\Enums\IdeaStatus;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Comment;

class IdeaController extends Controller
{
    public function index(Request $request, ListIdea $action)
    {

        $ideas = $action->handle($request->all());

        return view('ideas.index', [
            'ideas' => $ideas,
            'counts' => Idea::statusCounts($ideas),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IdeaRequest $request, CreateIdea $action)
    {
        $action->handle($request->safe()->all());

        return redirect()->route('idea.index')->with('success', 'Idea created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea)
    {
        Gate::authorize('canView', $idea);

        $idea->load('comments.user');

        return view('ideas.show', [
            'idea' => $idea,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IdeaRequest $request, Idea $idea, UpdateIdea $action)
    {

        Gate::authorize('canModify', $idea);

        $action->handle($request->safe()->all(), $idea);

        return redirect()->route('idea.show', $idea)->with('success', 'Idea updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        // authorize first
        Gate::authorize('canModify', $idea);

        $idea->delete();

        return redirect()->route('idea.index')->with('success', 'Idea deleted successfully!');
    }
}
