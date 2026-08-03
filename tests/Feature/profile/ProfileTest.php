<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('requires authentication to edit and update profile', function () {
    $this->get(route('profile.edit'))
        ->assertRedirect(route('login'));

    $this->patch(route('profile.update'), [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ])->assertRedirect(route('login'));
});

it('updates the authenticated user profile details', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);

    $response = $this->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

    $response->assertRedirect(route('idea.index'));
    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);
});

it('keeps the existing password when password is not provided', function () {
    $user = User::factory()->create([
        'password' => 'password12345',
    ]);

    $originalPasswordHash = $user->password;

    $response = $this->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

    $response->assertRedirect(route('idea.index'));
    $response->assertSessionHasNoErrors();

    $user->refresh();

    expect($user->password)->toBe($originalPasswordHash);
});

it('updates password when a new password is provided', function () {
    $user = User::factory()->create([
        'email' => 'original@example.com',
        'password' => 'password12345',
    ]);

    $response = $this->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'password' => 'newPassword12345',
            'password_confirmation' => 'newPassword12345',
        ]);

    $response->assertRedirect(route('idea.index'));
    $response->assertSessionHasNoErrors();

    $user->refresh();

    expect(Hash::check('newPassword12345', $user->password))->toBeTrue();
});

it('validates unique email when updating profile', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $user = User::factory()->create(['email' => 'current@example.com']);

    $response = $this->actingAs($user)
        ->from(route('profile.edit'))
        ->patch(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'taken@example.com',
        ]);

    $response->assertRedirect(route('profile.edit'));
    $response->assertSessionHasErrors(['email']);
});
