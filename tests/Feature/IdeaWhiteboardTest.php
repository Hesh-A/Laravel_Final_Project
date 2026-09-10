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