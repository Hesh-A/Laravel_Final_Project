<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Idea;
use Illuminate\Support\Facades\DB;

class UpdateIdea
{
    public function handle(array $attributes, Idea $idea): Idea
    {
        $data = collect($attributes)->only(['title', 'description', 'status', 'image_path', 'links'])
            ->toArray();

        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
        }

        // create the steps

        return DB::transaction(function () use ($data, $attributes, $idea): Idea {
            $idea->update($data);

            $idea->steps()->delete();

            $idea->steps()->createMany($attributes['steps'] ?? []);

            return $idea;
        });

    }
}
