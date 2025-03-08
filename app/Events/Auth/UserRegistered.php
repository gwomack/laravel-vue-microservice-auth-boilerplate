<?php

namespace App\Events\Auth;

class UserRegistered extends BaseAuthEvent
{
    public function getEventType(): string
    {
        return 'user.registered';
    }
} 