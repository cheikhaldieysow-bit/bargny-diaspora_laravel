<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\RegisterMailService;
use App\Http\Requests\Auth\RegisterWithRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterMailService $registerMailService,
        private readonly AuthService $authService
    ) {}

    /**
     * POST /api/auth/register
     */
    public function register(RegisterWithRequest $request): JsonResponse
    {
        $userDTO = $this->registerMailService->register(
            $request->validated()
        );

        return response()->json([
            'message' => 'Inscription réussie',
            'data'    => $userDTO,
        ], 201);
    }

    // login() / logout() pourront être ajoutés plus tard

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login($request->login, $request->password);

        if (!$result) {
            return response()->json([
                'message' => 'Identifiants invalides'
            ], 401);
        }

        return response()->json([
            'message' => 'Connexion réussie',
            'access_token' => $result['token'],
            'token_type' => 'Bearer',
            'user' => $result['user']
        ]);
    }
    // logout()
    public function logout(Request $request): JsonResponse
{
    // Supprime le token actuel 
    $request->user()->currentAccessToken()->delete();

    // supprimer tous les tokens pour  déconnecter l'utilisateur sur tous les appareils
    // $request->user()->tokens()->delete();

    // Log de déconnexion pour audit (optionnel mais recommandé)
    \Log::info('Utilisateur déconnecté', ['user_id' => $request->user()->id]);

    // Réponse JSON avec headers de sécurité pour cache
    return response()->json([
        'message' => 'Déconnexion réussie'
    ], 200)
    ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
    ->header('Pragma', 'no-cache')
    ->header('Expires', '0');
}
}
