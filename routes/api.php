<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 🔓 Auth Google (PUBLIC)
Route::get('/auth/google/redirect', [AuthController::class, 'redirect']);
Route::get('/auth/google/callback', [AuthController::class, 'callback']);


// 🔐 Routes protégées par Sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
