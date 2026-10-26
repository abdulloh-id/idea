<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Idea;
use Illuminate\Support\Facades\DB;

class UpdateIdea
{
    public function handle(array $attributes, Idea $idea): void
    {
        $data = collect($attributes)->only([
            'title', 'description', 'status', 'links',
        ])->toArray();

        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
        }

        DB::transaction(function () use ($idea, $data, $attributes) {
            $idea->update($data);

            $idea->steps()->delete();

            $steps = collect($attributes['steps'] ?? [])->map(fn ($step) => [
                'description' => $step['description'],
                'completed' => filter_var($step['completed'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ]);

            $idea->steps()->createMany($steps);
        });
    }
}
