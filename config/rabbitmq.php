<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RabbitMQ Exchanges Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the exchanges used for different types of events.
    | Using topic exchanges allows for flexible routing patterns.
    |
    */
    'exchanges' => [
        'user.events' => [
            'type' => 'topic',
            'durable' => true,
            'auto_delete' => false,
            'internal' => false,
            'queues' => [
                'user.registration' => [
                    'routing_keys' => ['user.registered'],
                    'durable' => true,
                ],
                'user.authentication' => [
                    'routing_keys' => ['user.login', 'user.logout'],
                    'durable' => true,
                ],
                'user.verification' => [
                    'routing_keys' => ['user.verified'],
                    'durable' => true,
                ],
                'user.password' => [
                    'routing_keys' => ['user.password_reset'],
                    'durable' => true,
                ],
            ],
        ],
        'auth.events' => [
            'type' => 'topic',
            'durable' => true,
            'auto_delete' => false,
            'internal' => false,
            'queues' => [
                'auth.security' => [
                    'routing_keys' => ['auth.failed_attempt', 'auth.suspicious_activity'],
                    'durable' => true,
                ],
                'auth.monitoring' => [
                    'routing_keys' => ['auth.#'], // Captures all auth events for monitoring
                    'durable' => true,
                ],
            ],
        ],
    ],
]; 