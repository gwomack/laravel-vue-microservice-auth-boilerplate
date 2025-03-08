<?php

namespace App\Listeners;

use App\Events\Auth\BaseAuthEvent;
use App\Jobs\AuthenticationEvent;
use Illuminate\Events\Dispatcher;

class AuthEventSubscriber
{
    public function handleAuthEvent(BaseAuthEvent $event): void
    {
        AuthenticationEvent::dispatch(
            $event->getEventType(),
            $event->toArray(),
            null
        );
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            'App\Events\Auth\*' => 'handleAuthEvent',
        ];
    }
} 