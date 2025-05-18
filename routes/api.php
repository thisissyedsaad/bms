<?php

use App\Http\Controllers\Backend\Api\SiteController;
use App\Http\Controllers\Backend\Api\AuthController;
use App\Http\Controllers\Backend\Api\PsychicController;
use App\Http\Controllers\Backend\Api\PsychicServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('customer.api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Psychic Routes
Route::middleware(['api', 'api.key'])->group(function () {
    Route::get('/psychics/top', [PsychicController::class, 'getTopPsychics']);
    Route::get('/site-settings', [SiteController::class, 'index']);
    Route::get('/psychics', [PsychicController::class, 'getAllPsychics']);
    Route::get('/psychics/{id}', [PsychicController::class, 'show'])->name('psychic.profile');
    Route::get('/psychic-services', [PsychicServiceController::class, 'getPsychicsServices']);
    Route::get('/psychic-services/{id}', [PsychicServiceController::class, 'showPsychicsService']);
});
