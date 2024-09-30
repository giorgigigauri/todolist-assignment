<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('can authenticate a user with valid credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $credentials = [
        'email' => $user->email,
        'password' => 'password123',
    ];

    $response = $this->postJson(route('auth'), $credentials);

    $response->assertStatus(200)
        ->assertJsonStructure(['token']);
});

it('cannot authenticate a user with invalid credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $credentials = [
        'email' => $user->email,
        'password' => 'wrongpassword', // Invalid password
    ];

    $response = $this->postJson(route('auth'), $credentials);

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'The provided credentials are incorrect.',
            'errors' => [
                'email' => ['The provided credentials are incorrect.'],
            ],
        ]);
});

it('cannot authenticate a non-existing user', function () {
    $credentials = [
        'email' => 'nonexisting@example.com',
        'password' => 'somepassword',
    ];

    $response = $this->postJson(route('auth'), $credentials);

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'The provided credentials are incorrect.',
            'errors' => [
                'email' => ['The provided credentials are incorrect.'],
            ],
        ]);
});
