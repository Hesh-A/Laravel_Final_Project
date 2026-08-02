<?php

namespace App\Actions;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\DB;

class CreateIdea
{
    public function __construct(#[CurrentUser] protected ?User $user = null)
    {
        //
    }

    public function handle(array $attributes): Idea
    {
        $user = $this->user ?? auth()->user();

        $data = collect($attributes)->only(['title', 'description', 'status', 'image_path', 'links'])
            ->toArray();

        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
        }

        // create the steps

        return DB::transaction(function () use ($data, $attributes, $user) {
            $idea = $user->ideas()->create($data);

            $steps = collect($attributes['steps'] ?? [])->map(fn ($step) => ['description' => $step])->toArray();

            $idea->steps()->createMany($steps);

            return $idea;
        });

    }
}
