<?php

declare(strict_types=1);

namespace App\Actions;
use Illuminate\Support\Collection;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\DB;
use App\IdeaStatus;

class ListIdea
{

    public function handle(array $attributes): Collection
    {
       
        $status = IdeaStatus::tryFrom($attributes['status'] ?? '');

        $ideas = Idea::query()
        ->with('user') 
        ->when($status, fn ($query) => $query->where('status', $status))
        ->latest()
        ->get();

        return $ideas;
    }
}
