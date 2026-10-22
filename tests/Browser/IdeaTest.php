<?php

use App\Models\Idea;
use App\Models\User;

it('creates a new idea', function () {
    $this->actingAs($user = User::factory()->create());

    visit('/ideas')
        ->click('@create-idea-button')
        ->assertVisible('[data-test="submit-idea-button"]')
        ->fill('title', 'My First Idea')
        ->click('@button-status-completed')
        ->fill('description', 'This is my first idea description.')
        ->click('@submit-new-step-button')
        ->fill('@new-step', 'Do a thing')
        ->click('@submit-new-step-button')
        ->fill('@new-step', 'Do another thing')
        ->fill('@new-link', 'https://linear-code-mastery.com/')
        ->click('@submit-new-link-button')
        ->fill('@new-link', 'https://laravel.com/')
        ->click('@submit-new-link-button')
        ->click('@submit-idea-button')
        ->assertPathIs('/ideas');

    expect($user->ideas()->first())->not->toBeNull();

    expect($user->ideas()->first())->toMatchArray([
        'title' => 'My First Idea',
        'status' => 'completed',
        'description' => 'This is my first idea description.',
    ]);

    expect($idea->steps)->toHaveCount(2);
});

it('edits an existing idea', function () {
    $this->actingAs($user = User::factory()->create());

    $idea = Idea::factory()->for($user)->create();

    visit(route('idea.show', $idea))
        ->click('@edit-idea-button')
        ->assertVisible('[data-test="submit-idea-button"]')
        ->fill('title', 'My First Idea')
        ->click('@button-status-completed')
        ->fill('description', 'This is my first idea description.')
        ->click('@submit-new-step-button')
        ->fill('@new-step', 'Do a thing')
        ->click('@submit-new-step-button')
        ->fill('@new-step', 'Do another thing')
        ->fill('@new-link', 'https://linear-code-mastery.com/')
        ->click('@submit-new-link-button')
        ->fill('@new-link', 'https://laravel.com/')
        ->click('@submit-new-link-button')
        ->click('@submit-idea-button')
        ->assertPathIs('/ideas');

    expect($user->ideas()->first())->not->toBeNull();

    expect($user->ideas()->first())->toMatchArray([
        'title' => 'My First Idea',
        'status' => 'completed',
        'description' => 'This is my first idea description.',
    ]);

    expect($idea->steps)->toHaveCount(2);
});
