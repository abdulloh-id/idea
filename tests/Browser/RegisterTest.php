<?php

test('it registers a user', function () {
    visit('/register')
        ->fill('name', 'Jane Doe')
        ->fill('email', 'janedoe@example.net')
        ->fill('password', 'password123')
        ->click('@register-button')
        ->assertPathIs('/');

    $this->assertAuthenticated();

    expect(Auth::user())->toMatchArray([
        'name' => 'Jane Doe',
        'email' => 'janedoe@example.net',
    ]);
});

test('it requires a valid email address', function () {
    visit('/register')
        ->fill('name', 'Jane Doe')
        ->fill('email', 'janedoe.net')
        ->fill('password', 'password123')
        ->click('@register-button')
        ->assertPathIs('/register');
});
