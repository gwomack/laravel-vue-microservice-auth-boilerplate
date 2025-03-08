<?php

namespace App\Console\Commands;

use App\Services\RabbitMQService;
use Illuminate\Console\Command;

class SetupRabbitMQExchanges extends Command
{
    protected $signature = 'rabbitmq:setup';
    protected $description = 'Setup RabbitMQ exchanges and queues';

    public function handle()
    {
        $this->info('Setting up RabbitMQ exchanges and queues...');

        try {
            $rabbitmq = new RabbitMQService();
            $rabbitmq->setupExchangesAndQueues();
            
            $this->info('RabbitMQ setup completed successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to setup RabbitMQ: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 