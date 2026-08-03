<?php

use App\Models\User;
use App\Notifications\ProfileUpdatedNotification;
use Illuminate\Support\Facades\Notification;

it('requires authentication', function () {
    visit('/profile/edit')
        ->assertPathIs('/login');
});

it('updates the authenticated user profile', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);

    $this->actingAs($user);

    visit('/profile/edit')
        ->assertValue('name', $user->name)
        ->fill('name', 'Updated Name')
        ->fill('email', 'updated@example.com')
        ->click('@update-profile-button')
        ->assertSee('Profile updated successfully!')
        ->assertPathIs('/ideas');

    $user->refresh();

    expect($user)->toMatchArray([
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);
});

it('notifies the user when the account is updated ', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $originalEmail = $user->email;

    Notification::fake();

    visit('/profile/edit')
        ->assertValue('name', $user->name)
        ->fill('name', 'Updated Name')
        ->fill('email', 'updated@example.com')
        ->click('@update-profile-button')
        ->assertSee('Profile updated successfully!');

    Notification::assertSentOnDemand(ProfileUpdatedNotification::class, fn ($notification, $route, $notifiable) => $notifiable->routes['mail'] === $originalEmail);

});
