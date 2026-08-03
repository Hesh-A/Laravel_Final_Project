<?php

use App\Models\User;

it('logs out a user', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    $this->assertAuthenticatedAs($user);

    $this->post(route('logout'));

    $this->assertGuest();
});
