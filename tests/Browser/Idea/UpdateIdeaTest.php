<?php

use App\Models\Idea;
use App\Models\User;

it('shows the initial input state', function () {
    $this->actingAs($user = User::factory()->create());
    $idea = Idea::factory()
        ->for($user)
        ->hasSteps(2)
        ->create([
            'links' => ['https://laravel.com/', 'https://pestphp.com/'],
        ]);

    $browser = visit(route('idea.show', $idea))
        ->click('@edit-idea-button')
        ->assertValue('title', $idea->title)
        ->assertValue('description', $idea->description)
        ->assertAttribute('[name="status"]', 'value', $idea->status->value);

    $idea->steps->each(
        fn ($step, $index) => $browser->assertValue(
            "input[name='steps[{$index}][description]']",
            $step->description,
        ),
    );

    // Wrap in collect() to enable collection methods like each()
    collect($idea->links)->each(
        fn (string|int|float $link, $index) => $browser->assertValue(
            "input[name='links[{$index}]']",
            $link,
        ),
    );
});

it('updates an existing idea', function () {
    $this->actingAs($user = User::factory()->create());

    $idea = Idea::factory()->for($user)->create(['links' => []]);

    visit(route('idea.show', $idea))
        ->click('@edit-idea-button')
        ->assertVisible('[data-test="submit-idea-button"]')
        ->fill('title', 'My First Idea')
        ->click('@button-status-completed')
        ->fill('description', 'This is my first idea description.')
        ->fill('@new-step', 'Do a thing')
        ->click('@submit-new-step-button')
        ->fill('@new-step', 'Do another thing')
        ->click('@submit-new-step-button')
        ->fill('@new-link', 'https://linear-code-mastery.com/')
        ->click('@submit-new-link-button')
        ->fill('@new-link', 'https://laravel.com/')
        ->click('@submit-new-link-button')
        ->click('@submit-idea-button')
        ->assertRoute('idea.show', [$idea]);

    $idea->refresh();

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
