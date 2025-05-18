<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailConfirmationGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $customer = Auth::guard('customers')->user();
        if ($customer) {
            if ($customer->email_verified_at)
                session()->put('email_verified', true);
            else
                session()->put('email_verified', false);
        } else
            session()->put('email_verified', false);

        return $next($request);
    }
}
