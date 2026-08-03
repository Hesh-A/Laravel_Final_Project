<?php

use App\Models\Idea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('logs in a user with correct credentials', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => 'password',
    ]);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('idea.index'));
    $response->assertSessionHasNoErrors();
    $this->assertAuthenticatedAs($user);
});

it('does not log in a user with incorrect credentials', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => 'password',
    ]);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors();
    $this->assertGuest();
});

it('requires authentication for actions related to ideas', function () {
    $idea = Idea::factory()->create();

    $this->get(route('idea.show', $idea))
        ->assertRedirect(route('login'));

    $this->post(route('idea.store'), [
        'title' => 'My first idea',
        'description' => 'Feature test description',
        'status' => 'pending',
    ])->assertRedirect(route('login'));

    $this->patch(route('idea.update', $idea), [
        'title' => 'Updated idea',
        'description' => 'Updated description',
        'status' => 'completed',
    ])->assertRedirect(route('login'));

    $this->delete(route('idea.destroy', $idea))
        ->assertRedirect(route('login'));

});

it('requires authentication to edit a profile', function () {

    $this->get(route('profile.edit'))
        ->assertRedirect(route('login'));

    $this->patch(route('profile.update'), [
        'name' => 'Updated Name',
    ])->assertRedirect(route('login'));
});
