<?php

namespace App\Services;

use App\Jobs\AuthenticationEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class AuthenticationEventService
{
    /**
     * Dispatch an authentication event with enriched data
     */
    public function dispatchEvent(string $eventType, Model $user, array $additionalData = []): void
    {
        // Enrich the base user data
        $userData = [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'created_at' => $user->created_at->toIso8601String(),
            'last_login_at' => $user->last_login_at ?? null,
        ];

        // Add IP and User Agent information if available
        if (request()) {
            $userData['ip_address'] = request()->ip();
            $userData['user_agent'] = request()->userAgent();
        }

        // Merge any additional data
        $userData = array_merge($userData, $additionalData);

        // Log the event locally
        Log::info("Authentication event occurred: {$eventType}", [
            'user_id' => $user->id,
            'event_type' => $eventType
        ]);

        // Dispatch the event to RabbitMQ
        AuthenticationEvent::dispatch(
            $eventType,
            $userData,
            json_encode([
                'service' => config('app.name'),
                'environment' => config('app.env'),
                'timestamp' => now()->toIso8601String()
            ])
        );
    }

    /**
     * Handle failed authentication attempts
     */
    public function handleFailedAttempt(string $email, string $reason): void
    {
        $data = [
            'email' => $email,
            'reason' => $reason,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ];

        AuthenticationEvent::dispatch(
            'auth.failed_attempt',
            $data,
            'Failed authentication attempt'
        );
    }

    /**
     * Handle suspicious authentication activities
     */
    public function handleSuspiciousActivity(Model $user, string $activity): void
    {
        $data = [
            'user_id' => $user->id,
            'email' => $user->email,
            'activity' => $activity,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String()
        ];

        AuthenticationEvent::dispatch(
            'auth.suspicious_activity',
            $data,
            'Suspicious authentication activity detected'
        );
    }
} 