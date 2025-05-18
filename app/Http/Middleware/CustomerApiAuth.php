<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!$request->bearerToken()) {
                return ResponseHelper::unauthorizedResponse('Authentication token is missing');
            }

            // Set the guard to customer_api
            Auth::shouldUse('customer_api');

            if (!Auth::check()) {
                return ResponseHelper::unauthorizedResponse('Invalid or expired authentication token');
            }

            return $next($request);
        } catch (\Exception $e) {
            return ResponseHelper::errorResponse(
                'Authentication failed',
                Response::HTTP_UNAUTHORIZED,
                $e
            );
        }
    }
} 