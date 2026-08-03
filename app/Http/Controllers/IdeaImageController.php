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
        Gate::authorize('canAccess', $idea);

        Storage::disk('public')->delete($idea->image_path);

        // Delete the image associated with the idea
        $idea->update(['image_path' => null]);
        $idea->save();

        return redirect()->route('idea.show', $idea)->with('success', 'Image deleted successfully.');
    }
}
