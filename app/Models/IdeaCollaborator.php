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

    /** @return BelongsTo<Idea, $this> */
    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
