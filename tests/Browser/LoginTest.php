<?php

use App\Models\User;

it('logs in a user', function () {

    // Arrange
    User::factory()->create([
        'email' => 'janedoe@example.net',
        'password' => 'password123'
    ]);

    // Act
    visit('/login')
        ->fill('email', 'janedoe@example.net')
        ->fill('password', 'password123')
        ->click('@login-button')
        ->assertRoute('idea.index');

    // Assert
    $this->assertAuthenticated();
});

it('logs out a user', function () {

    // Arrange
    $user = User::factory()->create();

    $this->actingAs($user);

    // Act
    visit('/')
        ->click('@logout-button');

    // Assert
    $this->assertGuest();
});
