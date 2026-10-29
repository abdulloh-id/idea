<?php

declare(strict_types=1);

namespace App\Models;

use App\IdeaStatus;
use Database\Factories\IdeaFactory;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class Idea extends Model
{
    /** @use HasFactory<IdeaFactory> */
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
        $counts = $user
            ->ideas()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return collect(IdeaStatus::cases())
            ->mapWithKeys(
                fn ($status) => [
                    $status->value => $counts->get($status->value, 0),
                ],
            )
            ->put('all', $user->ideas()->count());
    }

    // An idea belongs to a user
    public function user(): BelongsTo
    {
        // Return what? Return one user
        return $this->belongsTo(User::class);
    }

    // An idea can have many steps
    public function steps(): HasMany
    {
        // Return what? Return many steps
        return $this->hasMany(Step::class);
    }

    public function formattedDescription(): Attribute
    {
        return Attribute::get(
            fn ($value, $attributes) => new HtmlString(
                str($attributes['description'])
                    ->markdown([
                        'html_input' => 'escape',
                        'allow_unsafe_links' => false,
                        'max_nesting_level' => 5,
                    ])
                    ->trim()
                    ->toString(),
            ),
        );
    }
}
