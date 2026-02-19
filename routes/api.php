<?php

use App\Http\Controllers\OwnerProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProjectSearchController;
use App\Http\Controllers\ProjectSubmitController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/google/login', [GoogleAuthController::class, 'login']);
    Route::post('/google/register', [GoogleAuthController::class, 'register']);

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/projects/{project}/submit', [ProjectSubmitController::class, 'submit'])->name('projects.submit');

    Route::put('/projects/{projectId}', [ProjectSubmitController::class, 'update'])->name('projects.update');
});


Route::get('/view-profile/{id}', [UserController::class, 'viewProfile'])->name('view-profile');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->get('projects/search', [ProjectSearchController::class, 'search'])->name('api.projects.search');

// 2. VOS ROUTES OWNER (Doivent être À L'EXTÉRIEUR de la fonction ci-dessus)
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('owner')->group(function () {

        // Lister tous les projets du propriétaire connecté
        Route::get('/projects', [OwnerProjectController::class, 'index']);

        // Voir les détails d'un projet spécifique du propriétaire
        Route::get('/projects/{project}', [OwnerProjectController::class, 'show']);

    });

});

