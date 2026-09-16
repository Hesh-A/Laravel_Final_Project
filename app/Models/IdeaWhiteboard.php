<?php

namespace App\Models;

use Database\Factories\IdeaWhiteboardFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdeaWhiteboard extends Model
{
    /** @use HasFactory<IdeaWhiteboardFactory> */
    use HasFactory;

    protected $fillable = [
        'drawing_data',
    ];

    protected function casts(): array
    {
        return [
            'drawing_data' => 'array',
        ];
    }

    /** @return BelongsTo<Idea, $this> */
    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class);
    }
}
