<?php

namespace App\Providers;

use App\Services\RabbitMQService;
use Illuminate\Support\ServiceProvider;

class RabbitMQServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RabbitMQService::class, function ($app) {
            return new RabbitMQService();
        });
    }

    public function boot(): void
    {
        // Run setup only in non-console environment or when running specific commands
        if (!$this->app->runningInConsole() || 
            in_array($this->app->request->server->get('argv')[1] ?? '', ['queue:work', 'rabbitmq:setup'])) {
            try {
                $service = $this->app->make(RabbitMQService::class);
                $service->setupExchangesAndQueues();
            } catch (\Exception $e) {
                \Log::error('Failed to setup RabbitMQ: ' . $e->getMessage());
            }
        }
    }
} 