<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StepController extends Controller
{
    public function update(Request $request, Step $step)
    {
        Gate::authorize('canModify', $step->idea);

        $step->update(['is_completed' => ! $step->is_completed]);

        return back()->with('success', 'Step updated successfully!');

    }
}
