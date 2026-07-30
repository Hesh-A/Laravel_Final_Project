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
		->fill('@new-link', 'https://www.example.com')
		->click('@add-new-link-button')
		->fill('@new-link', 'https://www.laravel.com')
		->click('@add-new-link-button')
		->click('@store-idea-button')
		->assertPathIs('/ideas');

	expect($user->ideas()->first())->toMatchArray([
		'title' => 'Build something',
		'description' => 'Create happy path.',
		'status' => 'pending',
		'links' => ['https://www.example.com', 'https://www.laravel.com'],
	]);
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
