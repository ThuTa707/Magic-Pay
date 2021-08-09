<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {   
        // Redirect to admin login if admin_user is no authenticated
        if($request->is('admin')){
            return route('admin.login');
        }

        // Redirect to user login if user is no authenticated (Default)
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
