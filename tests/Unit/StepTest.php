<?php

use App\Models\Idea;
use App\Models\User;
use App\Models\Step;


it('creates a step', function () {

    $step = Step::factory()->create();
    expect($step)->toBeInstanceOf(Step::class);

    expect($step->id)->not->toBeNull();
});

it('belongs to an idea', function () {

    $step = Step::factory()->create();
    expect($step->idea)->toBeInstanceOf(Idea::class);
});




