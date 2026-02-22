<?php

use App\Http\Controllers\OwnerProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProjectSearchController;
use App\Http\Controllers\ProjectSubmitController;
use App\Http\Controllers\Api\PasswordResetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes publiques (sans authentification)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes de récupération de mot de passe
Route::post('/password/forgot', [PasswordResetController::class, 'forgotPassword']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);
Route::post('/password/verify-token', [PasswordResetController::class, 'verifyToken']);


// Routes protégées (nécessitent authentification via Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Authentification
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Profil utilisateur
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::post('/user/change-password', [AuthController::class, 'changePassword']);
});

/*
|--------------------------------------------------------------------------
| Route de test (optionnel - à retirer en production)
|--------------------------------------------------------------------------
*/

Route::get('/test', function () {
    return response()->json([
        'message' => 'API Laravel fonctionne correctement',
        'timestamp' => now()->toDateTimeString(),
    ]);
});


Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/google/login', [GoogleAuthController::class, 'login']);
    Route::post('/google/register', [GoogleAuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
     
   
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

