<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            // if ($request->url() == route('login')) {
            //     return redirect(RouteServiceProvider::HOME);
            // }

            if ($request->url() == route('admin.login')) {
                if (Auth::guard(null)->check()) {
                    return redirect(RouteServiceProvider::ADMIN);
                }
            }
        }

        return $next($request);
    }
}
