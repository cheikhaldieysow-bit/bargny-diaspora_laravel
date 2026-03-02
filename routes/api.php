<?php

use App\Http\Controllers\OwnerProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProjectSearchController;
use App\Http\Controllers\ProjectSubmitController;
//use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Toutes les routes sont préfixées par /api (voir RouteServiceProvider)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Route pour les membres (authentification spécifique)
|--------------------------------------------------------------------------
*/

// Routes de connexion publiques
Route::post('/membre/login', [MembreController::class, 'loginMembre']);

// Routes protégées pour les membres
Route::middleware(['auth:sanctum', 'membre'])->group(function () {
    Route::post('/membre/logout', [MembreController::class, 'logoutMembre']);
    Route::get('/membre/profil', [MembreController::class, 'user']);
    Route::apiResource('membres', MembreController::class);
});

/*
|--------------------------------------------------------------------------
| Route de test (optionnel - à retirer en production)
| Routes publiques (sans authentification)
|--------------------------------------------------------------------------
*/

// Route de test (à retirer en production)
Route::get('/test', function () {
    return response()->json([
        'message' => 'API Laravel fonctionne correctement',
        'timestamp' => now()->toDateTimeString(),
    ]);
});

// Routes d'authentification
Route::prefix('auth')->group(function () {
    // Authentification classique
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Authentification Google
    Route::post('/google/login', [GoogleAuthController::class, 'login']);
    Route::post('/google/register', [GoogleAuthController::class, 'register']);

    // Récupération de mot de passe
    Route::post('/password/forgot', [PasswordResetController::class, 'forgotPassword']);
    Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);
    Route::post('/password/verify-token', [PasswordResetController::class, 'verifyToken']);
});

// Profils utilisateurs publics
Route::get('/users/{id}/profile', [UserController::class, 'viewProfile'])->name('view-profile');

/*
|--------------------------------------------------------------------------
| Routes protégées (authentification requise)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Authentification et profil utilisateur
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });

    // Gestion du profil utilisateur
    Route::prefix('user')->group(function () {
        Route::get('/', function (Request $request) {
            return $request->user();
        });

        // Modification du profil
        Route::put('/profile/name', [ProfileController::class, 'updateName']);
        Route::post('/profile/email/initiate', [ProfileController::class, 'initiateEmailChange']);
        Route::post('/profile/email/verify', [ProfileController::class, 'verifyEmailChange']);
        Route::post('/profile/phone/initiate', [ProfileController::class, 'initiatePhoneChange']);
        Route::post('/profile/phone/verify', [ProfileController::class, 'verifyPhoneChange']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);

        // Suppression de compte
        Route::delete('/account', [ProfileController::class, 'deleteAccount']);
    });

    // Gestion des projets
    Route::prefix('projects')->group(function () {
        // Recherche de projets
        Route::get('/search', [ProjectSearchController::class, 'search'])->name('api.projects.search');

        // Soumission et modification de projets
        Route::post('/{project}/submit', [ProjectSubmitController::class, 'submit'])->name('projects.submit');
        Route::put('/{projectId}', [ProjectSubmitController::class, 'update'])->name('projects.update');

        // Suppression de projet (si non financé)
        Route::delete('/{project}', [ProjectController::class, 'destroy']);
    });

    // Routes propriétaire de projets
    Route::prefix('owner')->group(function () {
        Route::prefix('projects')->group(function () {
            // Lister tous les projets du propriétaire connecté
            Route::get('/', [OwnerProjectController::class, 'index']);

            // Voir les détails d'un projet spécifique du propriétaire
            Route::get('/{project}', [OwnerProjectController::class, 'show']);
        });
    });

    
});
    