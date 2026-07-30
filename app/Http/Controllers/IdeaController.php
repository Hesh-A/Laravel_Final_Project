<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use App\Models\Idea;
use App\Http\Requests\StoreIdeaRequest;
use App\Http\Requests\UpdateIdeaRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\IdeaStatus;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
 
        $user = Auth::user();

        $status = IdeaStatus::tryFrom($request->status ?? '');

        $ideas = $user
        ->ideas()
        ->when($status, fn($query, $status) => $query->where('status', $status))
        ->get();

        return view('ideas.index', [
            'ideas' => $ideas,
            'counts' => Idea::statusCounts($user),
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
    public function store(StoreIdeaRequest $request)
    {
        $user = Auth::user();

        $idea = $user->ideas()->create($request->validated());

        return redirect()->route('idea.index')->with('success', 'Idea created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea)
    {
        return view('ideas.show', [
            'idea' => $idea,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIdeaRequest $request, Idea $idea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        //authorize first

        $idea->delete();

        return redirect()->route('idea.index')->with('success', 'Idea deleted successfully!');
    }
}
