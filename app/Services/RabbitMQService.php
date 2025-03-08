<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\RabbitMQQueue;
use Illuminate\Support\Facades\Queue;

class RabbitMQService
{
    private RabbitMQQueue $queue;

    public function __construct()
    {
        $this->queue = Queue::connection('rabbitmq');
    }

    /**
     * Setup exchanges and queues based on configuration
     */
    public function setupExchangesAndQueues(): void
    {
        $exchanges = config('rabbitmq.exchanges');

        foreach ($exchanges as $exchangeName => $exchangeConfig) {
            $this->queue->getChannel()->exchange_declare(
                $exchangeName,
                $exchangeConfig['type'],
                false,
                $exchangeConfig['durable'],
                $exchangeConfig['auto_delete'],
                $exchangeConfig['internal']
            );

            foreach ($exchangeConfig['queues'] as $queueName => $queueConfig) {
                $this->queue->getChannel()->queue_declare(
                    $queueName,
                    false,
                    $queueConfig['durable'],
                    false,
                    false
                );

                foreach ($queueConfig['routing_keys'] as $routingKey) {
                    $this->queue->getChannel()->queue_bind(
                        $queueName,
                        $exchangeName,
                        $routingKey
                    );
                }
            }
        }
    }

    /**
     * Publish message to exchange
     */
    public function publishMessage(string $exchange, string $routingKey, array $data): void
    {
        try {
            $this->queue->pushRaw(
                json_encode($data),
                $routingKey,
                [
                    'exchange' => $exchange,
                    'exchange_type' => 'topic',
                ]
            );

            Log::info("Message published to RabbitMQ", [
                'exchange' => $exchange,
                'routing_key' => $routingKey
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to publish message to RabbitMQ", [
                'exchange' => $exchange,
                'routing_key' => $routingKey,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
} 