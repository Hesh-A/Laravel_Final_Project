<?php

declare(strict_types=1);

namespace App\Models;

use App\CollaborationStatus;
use App\IdeaStatus;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Idea extends Model
{
    /** @use HasFactory<IdeaFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::deleting(function (Idea $idea): void {
            if ($idea->image_path) {
                Storage::disk('public')->delete($idea->image_path);
            }
        });
    }

    protected $casts = [
        'links' => AsArrayObject::class,
        'status' => IdeaStatus::class,
    ];

    protected $attributes = [
        'status' => IdeaStatus::PENDING->value,
    ];

    public static function statusCounts(Collection $ideas): Collection
    {
        $statusCounts = Idea::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return collect(IdeaStatus::cases())
            ->mapWithKeys(fn ($status) => [$status->value => $statusCounts->get($status->value, 0)])
            ->put('all', $ideas->count());

    }

    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function isCollaborator(User $user): bool
    {
        return $this->collaborators()->where('user_id', $user->id)->where('status', CollaborationStatus::APPROVED)->exists();
    }

    public function collaborators(): HasMany
    {
        return $this->hasMany(IdeaCollaborator::class);
    }
}
