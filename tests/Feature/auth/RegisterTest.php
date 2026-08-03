<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('successfully registers a user', function () {

    $response = $this->post(route('register'), [
        'name' => 'John Doe',
        'email' => 'johndoe@mail.com',
        'password' => 'password12345',
    ]);

    $response->assertRedirect(route('idea.index'));
    $response->assertSessionHasNoErrors();

    $user = User::where('email', 'johndoe@mail.com')->first();

    expect($user)->not->toBeNull();
    $this->assertAuthenticatedAs($user);
    expect(Hash::check('password12345', $user->password))->toBeTrue();
});
