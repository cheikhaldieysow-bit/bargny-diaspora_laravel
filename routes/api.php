<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectSearchController;
use App\Http\Controllers\ProjectSubmitController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware(['auth'])->group(function () {
    Route::post('/projects/{project}/submit', [ProjectSubmitController::class, 'submit'])
         ->name('projects.submit');
});

Route::middleware('auth:sanctum')->get('projects/search', [ProjectSearchController::class, 'search'])
    ->name('api.projects.search');
