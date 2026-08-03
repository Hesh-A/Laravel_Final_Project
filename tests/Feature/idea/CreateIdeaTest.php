<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores an idea for an authenticated user', function () {
    $user = User::factory()->create();

    $payload = [
        'title' => 'My first idea',
        'description' => 'Feature test description',
        'status' => 'pending',
        'links' => ['https://example.com'],
        'steps' => [
            ['description' => 'Do first step', 'is_completed' => false],
        ],
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('idea.store'), $payload);

    $response->assertStatus(302);

    $this->assertDatabaseHas('ideas', [
        'title' => 'My first idea',
        'user_id' => $user->id,
    ]);

    $this->assertDatabaseHas('steps', [
        'description' => 'Do first step',
    ]);
});
