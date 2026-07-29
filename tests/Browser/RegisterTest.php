<?php

use Illuminate\Support\Facades\Auth;

it('registers a user', function () {
    visit('/register')
        ->fill('name', 'Johas ')
        ->fill('email', 'johndoe@mail.com')
        ->fill('password', 'password12345')
        ->click('@register-button')
        ->assertPathIs('/');

    $this->assertAuthenticated();

    expect(Auth::user())->toMatchArray([
        'name' => 'Johas',
        'email' => 'johndoe@mail.com',
    ]);
});


