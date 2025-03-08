<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Services\RabbitMQService;

class AuthenticationEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $eventType,
        private array $userData,
        private ?string $additionalInfo = null
    ) {
        $this->queue = 'auth_events';
        $this->onQueue('auth_events');
        
        // Add retry and timeout configurations
        $this->tries = 3;
        $this->timeout = 30;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $eventData = [
            'event_type' => $this->eventType,
            'user_data' => $this->userData,
            'additional_info' => $this->additionalInfo,
            'timestamp' => now()->toIso8601String(),
            'service_info' => [
                'name' => config('app.name'),
                'environment' => config('app.env'),
                'version' => config('app.version', '1.0.0')
            ]
        ];

        // Log the authentication event
        Log::info('Authentication event processed', $eventData);

        try {
            // Handle the event based on its type
            match ($this->eventType) {
                'user.registered' => $this->handleUserRegistered($eventData),
                'user.login' => $this->handleUserLogin($eventData),
                'user.logout' => $this->handleUserLogout($eventData),
                'user.password_reset' => $this->handlePasswordReset($eventData),
                'user.verified' => $this->handleEmailVerified($eventData),
                'auth.failed_attempt' => $this->handleFailedAttempt($eventData),
                'auth.suspicious_activity' => $this->handleSuspiciousActivity($eventData),
                default => $this->handleDefaultEvent($eventData),
            };
        } catch (\Exception $e) {
            Log::error('Failed to process authentication event', [
                'event_type' => $this->eventType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Handle user registration events
     */
    private function handleUserRegistered(array $eventData): void
    {
        $this->publishToExchange('user.events', 'user.registered', $eventData);
        
        // Additional registration-specific logic
        Log::info('New user registered', [
            'user_id' => $eventData['user_data']['id'],
            'email' => $eventData['user_data']['email']
        ]);
    }

    /**
     * Handle user login events
     */
    private function handleUserLogin(array $eventData): void
    {
        $this->publishToExchange('user.events', 'user.login', $eventData);
        
        // Track login activity
        Log::info('User logged in', [
            'user_id' => $eventData['user_data']['id'],
            'ip' => $eventData['user_data']['ip_address'] ?? 'unknown'
        ]);
    }

    /**
     * Handle user logout events
     */
    private function handleUserLogout(array $eventData): void
    {
        $this->publishToExchange('user.events', 'user.logout', $eventData);
    }

    /**
     * Handle password reset events
     */
    private function handlePasswordReset(array $eventData): void
    {
        $this->publishToExchange('user.events', 'user.password_reset', $eventData);
        
        Log::info('User password reset', [
            'user_id' => $eventData['user_data']['id']
        ]);
    }

    /**
     * Handle email verification events
     */
    private function handleEmailVerified(array $eventData): void
    {
        $this->publishToExchange('user.events', 'user.verified', $eventData);
    }

    /**
     * Handle failed authentication attempts
     */
    private function handleFailedAttempt(array $eventData): void
    {
        $this->publishToExchange('auth.events', 'auth.failed_attempt', $eventData);
        
        Log::warning('Failed authentication attempt', [
            'email' => $eventData['user_data']['email'],
            'ip' => $eventData['user_data']['ip_address'] ?? 'unknown'
        ]);
    }

    /**
     * Handle suspicious activity events
     */
    private function handleSuspiciousActivity(array $eventData): void
    {
        $this->publishToExchange('auth.events', 'auth.suspicious_activity', $eventData);
        
        Log::warning('Suspicious authentication activity detected', [
            'user_id' => $eventData['user_data']['id'] ?? 'unknown',
            'activity' => $eventData['user_data']['activity'] ?? 'unknown'
        ]);
    }

    /**
     * Handle default/unknown events
     */
    private function handleDefaultEvent(array $eventData): void
    {
        $this->publishToExchange('auth.events', 'auth.unknown', $eventData);
    }

    /**
     * Publish event to RabbitMQ exchange
     */
    private function publishToExchange(string $exchange, string $routingKey, array $data): void
    {
        $rabbitmq = new RabbitMQService();
        $rabbitmq->publishMessage($exchange, $routingKey, $data);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Authentication event failed to process', [
            'event_type' => $this->eventType,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
} 