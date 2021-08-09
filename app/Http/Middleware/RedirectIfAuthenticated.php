<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {   
        if (Auth::guard($guard)->check()) {

             // Redirect to admin home if auth admin_user try to login again 
            if($guard == 'admin_user'){
            return redirect(RouteServiceProvider::ADMINPANEL);
            }
            

             // Redirect to user home if auth user try to login again 
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
