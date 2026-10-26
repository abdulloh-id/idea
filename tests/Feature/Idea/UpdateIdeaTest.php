<?php

// tests/Feature/Idea/UpdateIdeaTest.php

use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\UploadedFile;

it('updates an existing idea via feature test', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();

    $response = $this->actingAs($user)->patch(route('idea.update', $idea), [
        'title' => 'My First Idea',
        'status' => 'completed',
        'description' => 'This is my first idea description.',
        'steps' => [
            ['description' => 'Do a thing'],
            ['description' => 'Do another thing'],
        ],
        'image' => UploadedFile::fake()->image('updated_thumbnail.jpg'),
        'links' => ['https://linear-code-mastery.com/', 'https://laravel.com/'],
    ]);

    $response->assertRedirect(route('idea.show', $idea));

    $idea->refresh();

    // Assert main table attributes
    expect($idea)->toMatchArray([
        'title' => 'My First Idea',
        'status' => 'completed',
        'description' => 'This is my first idea description.',
    ]);

    // Convert ArrayObject to a plain PHP array using getArrayCopy()
    expect($idea->links->getArrayCopy())->toEqual([
        'https://linear-code-mastery.com/',
        'https://laravel.com/',
    ]);

    // Assert steps relationship count and values
    expect($idea->steps)->toHaveCount(2);
    expect($idea->steps->pluck('description')->all())->toEqual([
        'Do a thing',
        'Do another thing',
    ]);
});
