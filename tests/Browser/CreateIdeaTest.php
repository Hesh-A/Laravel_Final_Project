<?php

use App\Models\Idea;
use App\Models\User;

it('creates an idea', function () {
	//when a user is authenticated
	$user = User::factory()->create();
	$this->actingAs($user);

	// the idea creation works
	visit('/ideas')
		->click('@create-idea-button')
		->fill('title', 'Build something')
        ->click('@status-button-pending')
		->fill('description', 'Create happy path.')
		->click('@store-idea-button')
		->assertPathIs('/ideas');

	expect(Idea::query()->where('title', 'Build something')->exists())->toBeTrue();
});

it('handles invalid idea creation', function () {
	//when a user is authenticated
	$user = User::factory()->create();
	$this->actingAs($user);

	// the create form rejects invalid data
	visit('/ideas')
		->click('@create-idea-button')
		->fill('title', ' ')
		->fill('description', ' ')
		->click('@store-idea-button')
		->assertPathIs('/ideas');

	expect(Idea::query()->count())->toBe(0);
});
