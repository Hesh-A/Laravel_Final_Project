<?php

use App\Models\User;

it('logs in a user', function () {
    // when a user exists in the database
    User::factory()->create([
        'email' => 'johndoe@mail.com',
        'password' => 'password12345',
    ]);
    // the login works
    visit('/login')
        ->fill('email', 'johndoe@mail.com')
        ->fill('password', 'password12345')
        ->click('@login-button')
        ->assertPathIs('/');
});

it('logs out a user', function () {
    // when a user exists in the database
    $user = User::factory()->create();

    $this->actingAs($user);

    // the logout works
    visit('/')
        ->click('@logout-button')
        ->assertPathIs('/login');

    $this->assertGuest();

});

it('handles invalid login', function () {
    visit('/login')
        ->fill('email', ' ')
        ->fill('password', ' ')
        ->click('@login-button')

        ->assertPathIs('/login');

});
