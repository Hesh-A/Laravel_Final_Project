<?php

use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('edits an idea', function () {
    // when a user is authenticated
    $user = User::factory()->create();

    $idea = Idea::factory()->for($user)->create([
        'title' => 'Build something',
        'description' => 'Create happy path.',
        'status' => 'pending',
        'links' => [],
    ]);

    $this->actingAs($user);

    // the idea update works
    visit(route('idea.show', $idea))
        ->click('@edit-idea-button')
        ->fill('title', 'Build something else')
        ->click('@status-button-pending')
        ->fill('description', 'Create happy path 2.0.')
        ->fill('@new-step', 'Step 1.1')
        ->click('@add-new-step-button')
        ->fill('@new-step', 'Step 2.2')
        ->click('@add-new-step-button')
        ->fill('@new-link', 'https://www.example.com')
        ->click('@add-new-link-button')
        ->fill('@new-link', 'https://www.laravel.com')
        ->click('@add-new-link-button')
        ->click('@update-idea-button')
        ->assertPathIs('/ideas/'.$idea->id);

    $idea = $idea->fresh();

    expect($idea)->toMatchArray([
        'title' => 'Build something else',
        'description' => 'Create happy path 2.0.',
        'status' => 'pending',
    ]);

    expect($idea->links->toArray())->toBe([
        'https://www.example.com',
        'https://www.laravel.com',
    ]);

});

it('removes an idea image from the edit modal', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $imagePath = UploadedFile::fake()->image('idea.jpg')->store('ideas', 'public');

    $idea = Idea::factory()->for($user)->create([
        'title' => 'Idea with image',
        'description' => 'Has an uploaded image.',
        'status' => 'pending',
        'image_path' => $imagePath,
        'links' => [],
    ]);

    Storage::disk('public')->assertExists($imagePath);

    visit(route('idea.show', $idea))
        ->click('@edit-idea-button')
        ->click('@delete-image-button')
        ->assertPathIs('/ideas/'.$idea->id);

    expect($idea->fresh()->image_path)->toBeNull();
    Storage::disk('public')->assertMissing($imagePath);
});
