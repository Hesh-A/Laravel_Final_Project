<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Idea;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\DB;

class CreateComment
{
    public function __construct(#[CurrentUser] protected ?User $user = null)
    {
        //
    }

    public function handle(array $attributes): Comment
    {
        $user = $this->user ?? auth()->user();

        $data = collect($attributes)->toArray();

        return DB::transaction(function () use ($data, $user) {
            $comment = $user->comments()->create($data);

            return $comment;
        });

    }
}
