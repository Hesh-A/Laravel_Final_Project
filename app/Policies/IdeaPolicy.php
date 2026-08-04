<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;

class IdeaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function canModify(User $user, Idea $idea): bool
    {
        return $idea->user->is($user);
    }

    public function canView(User $user, Idea $idea): bool
    {
        return true;
    }
}
