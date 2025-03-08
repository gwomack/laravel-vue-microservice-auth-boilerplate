<?php

namespace App\Events\Auth;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class BaseAuthEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        protected array $userData,
        protected ?string $additionalInfo = null
    ) {}

    abstract public function getEventType(): string;

    public function toArray(): array
    {
        return [
            'user_data' => $this->userData,
            'additional_info' => $this->additionalInfo,
            'timestamp' => now()->toIso8601String(),
        ];
    }
} 