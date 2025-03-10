<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

test('user can view login page', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->component('Auth/Login'));
});

test('user can login with correct credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123')
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password123'
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('user cannot login with incorrect password', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123')
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword'
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('user cannot login with non-existent email', function () {
    $response = $this->post('/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'password123'
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('user is remembered when remember option is selected', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123')
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
        'remember' => 'on'
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertNotNull($user->fresh()->remember_token);
});

test('validation fails with missing email', function () {
    $response = $this->post('/login', [
        'email' => '',
        'password' => 'password123'
    ]);

    $response->assertSessionHasErrors('email');
});

test('validation fails with invalid email format', function () {
    $response = $this->post('/login', [
        'email' => 'invalid-email',
        'password' => 'password123'
    ]);

    $response->assertSessionHasErrors('email');
});

test('validation fails with missing password', function () {
    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => ''
    ]);

    $response->assertSessionHasErrors('password');
}); 