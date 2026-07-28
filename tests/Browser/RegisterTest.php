<?php

it('registers a user', function () {
    visit('/register')
        ->fill('name', 'Johas ')
        ->fill('email', 'johndoe@mail.com')
        ->fill('password', 'password12345')
        ->click('[type="submit"]')
        ->assertPathIs('/');
});


