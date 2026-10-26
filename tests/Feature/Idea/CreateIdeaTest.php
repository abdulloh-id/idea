<?php

// tests/Feature/Idea/CreateIdeaTest.php

use App\Models\User;
use Illuminate\Http\UploadedFile;

it('creates a new idea via feature test', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('idea.store'), [
        'title' => 'My First Idea',
        'status' => 'completed',
        'description' => 'This is my first idea description.',
        'steps' => [
            ['description' => 'Do a thing'],
            ['description' => 'Do another thing'],
        ],
        'image' => UploadedFile::fake()->image('thumbnail.jpg'),
        'links' => ['https://linear-code-mastery.com/', 'https://laravel.com/'],
    ]);

    $response->assertRedirect('/ideas');

    $idea = $user->ideas()->first();

    expect($idea)->not->toBeNull();

    expect($idea)->toMatchArray([
        'title' => 'My First Idea',
        'status' => 'completed',
        'description' => 'This is my first idea description.',
    ]);

    expect($idea->links->getArrayCopy())->toEqual([
        'https://linear-code-mastery.com/',
        'https://laravel.com/',
    ]);

    expect($idea->steps)->toHaveCount(2);
    expect($idea->steps->pluck('description')->all())->toEqual([
        'Do a thing',
        'Do another thing',
    ]);
});
