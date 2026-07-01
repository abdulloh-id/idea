<?php

namespace App\Models;

use App\Models\Idea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Step extends Model
{
    /** @use HasFactory<\Database\Factories\StepFactory> */
    use HasFactory;

    protected $attributes = ['completed' => false];

    // A step belongs to an idea
    public function idea(): BelongsTo
    {
        // Return what? Return one idea
        return $this->belongsTo(Idea::class);
    }
}
