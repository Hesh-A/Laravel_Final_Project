<?php

it('registers a user', function () {
    visit('/register')
        ->fill('name', 'Johnny ')
        ->fill('email', 'johndoe@exple.com')
        ->fill('password', 'password23!@#')
        ->click('Register')
        ->assertPathIs('/');
});


