<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function canView(User $user, Comment $comment): bool
    {
        return true;
    }

    public function delete(User $user, Comment $comment): bool
    {
        $commentUser = $comment->user;
        $ideaOwner = $comment->idea->user;

        return $commentUser->is($user) || $ideaOwner->is($user);
    }
}
