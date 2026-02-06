<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('auth')->controller(AuthController::class)->group(function () {

    // Google
    Route::get('google/redirect', 'googleRedirect');
    Route::get('google/callback', 'googleCallback');
Route::post('/register', [AuthController::class, 'register']);

    // Auth classique (Les autres)
    // Route::post('login', 'login');
    // Route::post('register', 'register');
});