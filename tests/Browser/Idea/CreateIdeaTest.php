<?php

use App\Models\User;

it('creates a new idea', function () {
    $this->actingAs($user = User::factory()->create());

    visit('/ideas')
        ->click('@create-idea-button')
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
        ->assertPathIs('/ideas');

    $idea = $user->ideas()->first();

    expect($idea)->not->toBeNull();

    expect($idea)->toMatchArray([
        'title' => 'My First Idea',
        'status' => 'completed',
        'description' => 'This is my first idea description.',
    ]);

    expect($idea->steps)->toHaveCount(2);
});
