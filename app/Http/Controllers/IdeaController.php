<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateIdea;
use App\Actions\UpdateIdea;
use App\Http\Requests\IdeaRequest;
use App\IdeaStatus;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class IdeaController extends Controller
{
    public function index(Request $request)
    {

        $user = Auth::user();

        $status = IdeaStatus::tryFrom($request->status ?? '');

        $ideas = $user
            ->ideas()
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->get();

        return view('ideas.index', [
            'ideas' => $ideas,
            'counts' => Idea::statusCounts($user),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
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

        Gate::authorize('canAccess', $idea);

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

        Gate::authorize('canAccess', $idea);

        $action->handle($request->safe()->all(), $idea);

        return redirect()->route('idea.show', $idea)->with('success', 'Idea updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        // authorize first
        Gate::authorize('canAccess', $idea);

        $idea->delete();

        return redirect()->route('idea.index')->with('success', 'Idea deleted successfully!');
    }
}
