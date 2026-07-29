<?php

namespace App\Models;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\IdeaStatus;
use App\Models\User;
use App\Models\Step;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    /** @use HasFactory<\Database\Factories\IdeaFactory> */
    use HasFactory;

    protected $casts = [
        'links' => AsArrayObject::class,
        'status' => IdeaStatus::class,
    ];

    protected $attributes = [
        'status' => IdeaStatus::PENDING->value,
    ];


    public static function statusCounts(User $user): Collection
{
        $statusCounts = $user->ideas()
        ->selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');

        return collect(IdeaStatus::cases())
        ->mapWithKeys(fn($status) => [$status->value => $statusCounts->get($status->value, 0)])
        ->put('all', $user->ideas()->count());

}

    
    public function user(): BelongsTo
    {

        return $this-> belongsTo(User::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }


}
