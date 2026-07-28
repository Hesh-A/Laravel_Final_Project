<?php

use App\Models\User;

it('logs in a user', function () {
    //when a user exists in the database
    User::factory()->create([
        'email' => 'johndoe@mail.com',
        'password' => 'password12345',
    ]);
    // the login works
    visit('/login')
        ->fill('email', 'johndoe@mail.com')
        ->fill('password', 'password12345')
        ->click('[type="submit"]')
        ->assertPathIs('/');
});


