<?php

namespace App\Models;

use App\CollaborationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdeaCollaborator extends Model
{
    use HasFactory;

    protected $table = 'idea_collaborators';

    protected $fillable = ['idea_id', 'user_id', 'status'];

    protected $casts = [
        'status' => CollaborationStatus::class,
    ];

    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
