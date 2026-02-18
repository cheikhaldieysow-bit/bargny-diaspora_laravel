<?php

use App\Http\Controllers\OwnerProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectSearchController;

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

Route::get('/view-profile/{id}', [UserController::class, 'viewProfile'])
    ->name('view-profile');

// 1. La route par défaut de Laravel (pour l'utilisateur connecté)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('projects/search', [ProjectSearchController::class, 'search'])
    ->name('api.projects.search');

// 2. VOS ROUTES OWNER (Doivent être À L'EXTÉRIEUR de la fonction ci-dessus)
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('owner')->group(function () {

        // Lister tous les projets du propriétaire connecté
        Route::get('/projects', [OwnerProjectController::class, 'index']);

        // Voir les détails d'un projet spécifique du propriétaire
        Route::get('/projects/{project}', [OwnerProjectController::class, 'show']);

    });

});

