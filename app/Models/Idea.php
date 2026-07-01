<?php

namespace App\Models;

use App\IdeaStatus;
use App\Models\Step;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Idea extends Model
{
    /** @use HasFactory<\Database\Factories\IdeaFactory> */
    use HasFactory;

    protected $casts = [
        'links' => AsArrayObject::class,
        'status' => IdeaStatus::class,
    ];

    protected $attributes = [
        'status' => IdeaStatus::PENDING
    ];

    // An idea belongs to a user
    public function user(): BelongsTo
    {
        // Return what? Return one user
        return $this->belongsTo(User::class);
    }

    // An idea can have many steps
    public function steps() : HasMany
    {
        // Return what? Return many steps
        return $this->hasMany(Step::class);
    }
}
