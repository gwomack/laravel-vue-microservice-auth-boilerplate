<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->component('Auth/Register'));
});

test('new users can register with valid data', function () {
    Event::fake();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);

    Event::assertDispatched(Registered::class);
    
    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
    
    $user = User::where('email', 'test@example.com')->first();
    $this->assertNotNull($user);
    $this->assertEquals('Test User', $user->name);
    $this->assertTrue(Hash::check('password123', $user->password));
});

test('registration fails with existing email', function () {
    User::factory()->create([
        'email' => 'existing@example.com'
    ]);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('registration fails with mismatched passwords', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different123'
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertGuest();
});

test('registration fails with weak password', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => '123',
        'password_confirmation' => '123'
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertGuest();
});

test('name validation rules are enforced', function () {
    // Test empty name
    $response = $this->post('/register', [
        'name' => '',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);
    $response->assertSessionHasErrors('name');

    // Test name too long (>255 characters)
    $response = $this->post('/register', [
        'name' => str_repeat('a', 256),
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);
    $response->assertSessionHasErrors('name');
});

test('email validation rules are enforced', function () {
    // Test empty email
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => '',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);
    $response->assertSessionHasErrors('email');

    // Test invalid email format
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);
    $response->assertSessionHasErrors('email');

    // Test email too long
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => str_repeat('a', 246) . '@example.com', // 256+ chars
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);
    $response->assertSessionHasErrors('email');
});

test('successful registration triggers verification email', function () {
    Event::fake([Registered::class]);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);

    Event::assertDispatched(Registered::class, function ($event) {
        return $event->user->email === 'test@example.com';
    });

    $user = User::where('email', 'test@example.com')->first();
    $this->assertNull($user->email_verified_at);
});

test('email is converted to lowercase during registration', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'TEST@EXAMPLE.COM',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com'
    ]);
});

test('registration with valid data creates user with correct attributes', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);

    $user = User::where('email', 'test@example.com')->first();
    
    $this->assertNotNull($user);
    $this->assertInstanceOf(User::class, $user);
    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com'
    ]);
    $this->assertNotNull($user->created_at);
    $this->assertNotNull($user->updated_at);
    $this->assertNull($user->email_verified_at);
});
