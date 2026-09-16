<?php

use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('deletes an idea', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $idea = Idea::factory()->create(['user_id' => $user->id]);

    $response = $this->delete(route('idea.destroy', $idea));

    $response->assertRedirect(route('idea.index'));
    $response->assertSessionHasNoErrors();

    expect(Idea::find($idea->id))->toBeNull();
});

it('deletes the stored image when an idea is deleted', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $this->actingAs($user);

    $path = UploadedFile::fake()->image('idea.jpg')->store('ideas', 'public');

    $idea = Idea::factory()->create([
        'user_id' => $user->id,
        'image_path' => $path,
    ]);

    Storage::disk('public')->assertExists($path);

    $response = $this->delete(route('idea.destroy', $idea));

    $response->assertRedirect(route('idea.index'));
    $response->assertSessionHasNoErrors();

    expect(Idea::find($idea->id))->toBeNull();
    Storage::disk('public')->assertMissing($path);
});
