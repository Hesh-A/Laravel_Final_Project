<?php

namespace Database\Factories;

use App\CollaborationStatus;
use App\Models\Idea;
use App\Models\IdeaCollaborator;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IdeaCollaborator>
 */
class IdeaCollaboratorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'idea_id' => Idea::factory(),
            'user_id' => User::factory(),
            'status' => fake()->randomElement(CollaborationStatus::cases())->value,
        ];
    }
}
