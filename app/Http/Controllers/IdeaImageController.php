<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class IdeaImageController extends Controller
{
    public function destroy(Idea $idea)
    {
        // Check if the authenticated user is the owner of the idea
        Gate::authorize('canModify', $idea);

        Storage::disk('public')->delete($idea->image_path);

        $idea->update(['image_path' => null]);
        $idea->save();

        return redirect()->route('idea.show', $idea)->with('success', 'Image deleted successfully.');
    }
}
