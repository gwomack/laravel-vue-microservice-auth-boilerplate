<?php

namespace App\Providers;

use App\Services\AuthenticationEventService;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Failed;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(Registered::class, function ($event) {
            app(AuthenticationEventService::class)->dispatchEvent(
                'user.registered',
                $event->user,
                ['verification_status' => 'pending']
            );
        });

        Event::listen(Login::class, function ($event) {
            app(AuthenticationEventService::class)->dispatchEvent(
                'user.login',
                $event->user,
                ['login_type' => $event->remember ? 'remember_me' : 'standard']
            );
        });

        Event::listen(Logout::class, function ($event) {
            app(AuthenticationEventService::class)->dispatchEvent(
                'user.logout',
                $event->user
            );
        });

        Event::listen(PasswordReset::class, function ($event) {
            app(AuthenticationEventService::class)->dispatchEvent(
                'user.password_reset',
                $event->user,
                ['reset_method' => 'email']
            );
        });

        Event::listen(Verified::class, function ($event) {
            app(AuthenticationEventService::class)->dispatchEvent(
                'user.verified',
                $event->user,
                ['verification_method' => 'email']
            );
        });

        Event::listen(Failed::class, function ($event) {
            app(AuthenticationEventService::class)->handleFailedAttempt(
                $event->credentials['email'] ?? 'unknown',
                $event->message ?? 'Invalid credentials'
            );
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
