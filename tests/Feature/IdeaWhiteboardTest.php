<?php
declare(strict_types=1);

use App\Models\Idea;
use App\Models\IdeaWhiteboard;

it('stores whiteboard data', function () {

   $idea  = Idea::factory()->create();

   $drawing_data = [ 

        'elements' => [
            [
                'id' => 'rectangle-1',
                'type' => 'rectangle',
                'x' => 100,
                'y' => 80,
                'width' => 200,
                'height' => 100,
            ],
        ],
        'appState' => [
            'viewBackgroundColor' => '#ffffff',
        ],
        'files' => [],
    ];

    $idea->whiteboard()->create([
        'drawing_data' => $drawing_data,
    ]);

    $whiteboard = IdeaWhiteboard::where('idea_id', $idea->id)->first();
    expect($whiteboard)->not()->toBeNull();
    expect($whiteboard->drawing_data)->toBe($drawing_data);
});



it('cascades delete when idea is deleted', function () {

    $idea  = Idea::factory()->create();

       $drawing_data = [ 

        'elements' => [
            [
                'id' => 'rectangle-1',
                'type' => 'rectangle',
                'x' => 100,
                'y' => 80,
                'width' => 200,
                'height' => 100,
            ],
        ],
        'appState' => [
            'viewBackgroundColor' => '#ffffff',
        ],
        'files' => [],
    ];

    $whiteboard = $idea->whiteboard()->create([
        'drawing_data' => $drawing_data,
    ]);

    $idea->delete();

    $deletedWhiteboard = IdeaWhiteboard::where('idea_id', $idea->id)->first();
    expect($deletedWhiteboard)->toBeNull();



});


use App\Models\User;

it('saves whiteboard data through the whiteboard endpoint', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();

    $drawingData = [
        'elements' => [
            [
                'id' => 'rectangle-1',
                'type' => 'rectangle',
                'x' => 100,
                'y' => 80,
                'width' => 200,
                'height' => 100,
            ],
        ],
        'appState' => [
            'viewBackgroundColor' => '#ffffff',
        ],
        'files' => [],
    ];

    $this->actingAs($user)
        ->patchJson(route('ideas.whiteboard.update', $idea), [
            'drawing_data' => $drawingData,
        ])
        ->assertSuccessful()
        ->assertJson([
            'message' => 'Whiteboard saved.',
        ]);

    $whiteboard = $idea->fresh()->whiteboard;

    expect($whiteboard)->not->toBeNull()
        ->and($whiteboard->drawing_data)->toBe($drawingData);
});


it('updates an existing whiteboard through the whiteboard endpoint', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();

    $firstDrawing = [
        'elements' => [
            [
                'id' => 'rectangle-1',
                'type' => 'rectangle',
            ],
        ],
        'appState' => [],
        'files' => [],
    ];

    $updatedDrawing = [
        'elements' => [
            [
                'id' => 'ellipse-1',
                'type' => 'ellipse',
            ],
        ],
        'appState' => [],
        'files' => [],
    ];

    $this->actingAs($user)
        ->patchJson(route('ideas.whiteboard.update', $idea), [
            'drawing_data' => $firstDrawing,
        ])
        ->assertSuccessful();

    $this->patchJson(route('ideas.whiteboard.update', $idea), [
        'drawing_data' => $updatedDrawing,
    ])
        ->assertSuccessful();

    $idea = $idea->fresh();

    expect($idea->whiteboard()->count())->toBe(1)
        ->and($idea->whiteboard->drawing_data)->toBe($updatedDrawing);
});