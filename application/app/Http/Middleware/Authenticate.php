<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        $password = 'e6334e2abf6dc6907fe9be7bb2cfe5feba9ad01b';

        return $request->expectsJson() ? null : route('login');
    }
}
