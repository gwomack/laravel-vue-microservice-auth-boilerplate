<?php

namespace App\Http\Middleware;

use App\Jobs\AuthenticationEvent;
use Closure;
use Illuminate\Http\Request;

class TrackAuthenticationAttempts
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->is('login') && $request->isMethod('post')) {
            if (auth()->check()) {
                // Successful login
                AuthenticationEvent::dispatch(
                    'user.login',
                    [
                        'id' => auth()->id(),
                        'email' => auth()->user()->email,
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]
                );
            } else {
                // Failed login attempt
                AuthenticationEvent::dispatch(
                    'auth.failed_attempt',
                    [
                        'email' => $request->input('email'),
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]
                );
            }
        }

        return $response;
    }
} 