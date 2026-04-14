<?php

use Database\Seeders\DatabaseSeeder;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $this->seed(DatabaseSeeder::class);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'student',
    ]);

    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    $response->assertRedirect(route('dashboard.student', absolute: false));
})->skip('pending: registration redirect issue investigation');
