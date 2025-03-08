<?php

namespace App\Events\Auth;

class UserAuthenticated extends BaseAuthEvent
{
    public function getEventType(): string
    {
        return 'user.login';
    }
} 