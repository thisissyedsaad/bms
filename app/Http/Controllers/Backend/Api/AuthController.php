<?php

namespace App\Http\Controllers\Backend\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\CustomerDataResource;

class AuthController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:customers'],
                'password' => ['required', 'confirmed', 'min:8'],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
            ], [
                'email.required' => 'Email is required',
                'email.email' => 'Please enter a valid email address',
                'email.unique' => 'This email is already registered',
                'password.required' => 'Password is required',
                'password.confirmed' => 'Password confirmation does not match',
                'password.min' => 'Password must be at least 8 characters',
                'first_name.required' => 'First name is required',
                'last_name.required' => 'Last name is required',
            ]);

            // Start database transaction
            DB::beginTransaction();

            try {
                $customer = Customer::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'type' => 'private_seller',
                    'status' => 1,
                ]);

                // Create token
                $token = $customer->createToken('auth-token', ['*'])->plainTextToken;

                // Fire registration event
                // event(new CustomerRegistered($customer));

                // Commit transaction
                DB::commit();

                return ResponseHelper::successResponse([
                    'customer' => new CustomerDataResource($customer),
                    'token' => $token
                ], 'Your account has been created. Please verify your email');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Registration failed: ' . $e->getMessage());
                
                return ResponseHelper::errorResponse(
                    'Registration failed. Please try again later.',
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    $e
                );
            }
        } catch (ValidationException $e) {
            return ResponseHelper::validationErrorResponse($e->validator, 'Validation failed');
        }
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ], [
                'email.required' => 'Email is required',
                'email.email' => 'Please enter a valid email address',
                'password.required' => 'Password is required',
            ]);

            if (!Auth::guard('customers')->attempt($request->only('email', 'password'))) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $customer = Auth::guard('customers')->user();

            // Check if customer is active
            if (!$customer->getRawOriginal('status')) {
                Auth::guard('customers')->logout();
                return ResponseHelper::errorResponse(
                    'Your account is inactive. Please contact support.',
                    Response::HTTP_FORBIDDEN
                );
            }
            
            // Delete existing tokens
            $customer->tokens()->delete();
            
            // Create new token
            $token = $customer->createToken('auth-token', ['*'])->plainTextToken;

            return ResponseHelper::successResponse([
                'customer' => new CustomerDataResource($customer),
                'token' => $token
            ], 'Login successful');
        } catch (ValidationException $e) {
            return ResponseHelper::validationErrorResponse($e->validator, 'Validation failed');
        } catch (\Exception $e) {
            return ResponseHelper::errorResponse(
                'Login failed',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e
            );
        }
    }

    /**
     * Handle an incoming logout request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Set the guard to customer_api
            Auth::shouldUse('customer_api');

            // Get the current token
            $token = $request->bearerToken();

            if (!$token) {
                return ResponseHelper::errorResponse(
                    'No authentication token found',
                    Response::HTTP_UNAUTHORIZED
                );
            }

            // Get the current user
            $user = Auth::user();

            if (!$user) {
                return ResponseHelper::errorResponse(
                    'Invalid or expired authentication token',
                    Response::HTTP_UNAUTHORIZED
                );
            }

            // Revoke the current token
            $user->tokens()->delete();

            // Clear the current token from the request
            $request->headers->remove('Authorization');

            return ResponseHelper::successResponse(
                null,
                'Successfully logged out'
            );
        } catch (\Exception $e) {
            return ResponseHelper::errorResponse(
                'Logout failed',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e
            );
        }
    }
}
