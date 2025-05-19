<?php

use App\Http\Controllers\Backend\Admin\DashboardController;
use App\Http\Controllers\Backend\Admin\UserController;
use App\Http\Controllers\Backend\Admin\ClientController;
use App\Http\Controllers\Backend\Admin\ProjectController;
use App\Http\Controllers\Backend\Admin\SourceController;
use App\Http\Controllers\Backend\Admin\DatabaseBackupController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public Frontend Routes
Route::get('/', fn () => redirect('/admin/login'));
Route::get('/admin', fn () => redirect('/admin/login'));

require __DIR__ . '/auth.php';
// require __DIR__ . '/customer-auth.php';

// Admin Routes
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth']
], function () {
    Route::resource('users', 'UserController')->except(['show', 'create']);
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('sources', SourceController::class);
    Route::get('database/backup', [DatabaseBackupController::class, 'backup'])->name('database.backup');
});


