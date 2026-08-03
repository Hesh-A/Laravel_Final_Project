<?php

use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('handles image upload for an idea', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Storage::fake('public');

    $payload = [
        'title' => 'My first idea',
        'description' => 'Feature test description',
        'status' => 'pending',
        'image' => UploadedFile::fake()->image('idea.jpg'),
        'links' => ['https://example.com'],
    ];

    $response = $this->post(route('idea.store'), $payload);

    $response->assertStatus(302);
    $response->assertRedirect(route('idea.index'));
    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('ideas', [
        'title' => 'My first idea',
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    $idea = Idea::query()->where('title', 'My first idea')->first();

    expect($idea)->not->toBeNull();
    expect($idea->links->toArray())->toBe(['https://example.com']);
    expect($idea->image_path)->not->toBeNull();

    Storage::disk('public')->assertExists($idea->image_path);
});
