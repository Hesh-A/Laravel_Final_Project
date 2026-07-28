<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Idea;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    /** @use HasFactory<\Database\Factories\StepFactory> */
    use HasFactory;

    protected $attributes = [
        'is_completed' => false,
    ];

    public function idea() :BelongsTo
    {
        return $this->belongsTo(Idea::class);
    }
}
