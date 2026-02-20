<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\RegisterMailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterMailService $registerMailService,
    ) {}

    /**
     * POST /api/auth/register
     */
    public function register(Request $request): JsonResponse
    {
        $user = $this->registerMailService->register($request);

        return response()->json([
            'message' => 'Inscription réussie',
            'user'    => $user,
        ], 201);
    }

    /**
     * POST /api/auth/logout
     * Déconnexion de l'utilisateur (révoque le token actuel)
     */
    public function logout(Request $request): JsonResponse
    {
        // Supprime le token actuel utilisé pour l'authentification
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ], 200);
    }
}
