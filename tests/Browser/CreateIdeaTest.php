<?php

use App\Models\Idea;
use App\Models\User;

it("creates a new idea", function () {
    $this->actingAs($user = User::factory()->create());

    visit("/ideas")
        ->click("@create-idea-button")
        ->fill("title", "My First Idea")
        ->click("@button-status-completed") // <--- Click the Alpine status button directly
        ->fill("description", "This is my first idea description.")
        ->click("@submit-idea-button")
        ->assertPathIs("/ideas");

    expect($user->ideas()->first())->toMatchArray([
        "title" => "My First Idea",
        "status" => "completed",
        "description" => "This is my first idea description.",
    ]);
});
