<?php

use App\Models\Idea;
use App\Models\User;

test('it passes the normalized status to the ideas index view', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/ideas?status=not-real');

    $response->assertOk()
        ->assertViewHas('status', null);
});

test('it belongs to a user', function () {
    $idea = Idea::factory()->create();

    expect($idea->user)->toBeInstanceOf(User::class);
});

test('it can have steps', function () {
    $idea = Idea::factory()->create();

    expect($idea->steps)->toBeEmpty();

    $idea->steps()->create([
        'description' => 'Do the thing',
    ]);

    expect($idea->fresh()->steps)->toHaveCount(1);
});
