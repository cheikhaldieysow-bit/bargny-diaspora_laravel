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
        $this->authService->logout($request->user());
    
        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }
}
