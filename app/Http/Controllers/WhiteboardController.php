<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\UpdateWhiteboardRequest;
use App\Models\Idea;

class WhiteboardController extends Controller
{
    public function update (Idea $idea, UpdateWhiteboardRequest $request)

    {
        
       Gate::authorize('canModify', $idea);

       $idea->whiteboard()->updateOrCreate(
           [],
           ['drawing_data' => $request->validated('drawing_data')]
       );

        return response()->json([
            'message' => 'Whiteboard saved.',
        ]);
    }
}
