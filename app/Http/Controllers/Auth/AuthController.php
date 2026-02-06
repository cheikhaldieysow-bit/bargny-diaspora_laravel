<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\GoogleAuthService;
use App\Services\Auth\RegisterMailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\Factory as Socialite;

class AuthController extends Controller
{
    public function __construct(
        private readonly Socialite $socialite,
        private readonly GoogleAuthService $googleAuthService,
        private readonly RegisterMailService $registerMailService,
    ) {}

    /**
     * =========================
     * GOOGLE AUTH
     * =========================
     */

    // GET /auth/google/redirect
    public function googleRedirect()
    {
        return $this->socialite->driver('google')->stateless()->redirect();
    }

    // GET /auth/google/callback
    public function googleCallback(): JsonResponse
    {
        $googleUser = $this->socialite->driver('google')->stateless()->user();

        if (!$googleUser->getEmail()) {
            return response()->json([
                'message' => "Google n'a pas fourni d'email."
            ], 422);
        }

        $result = $this->googleAuthService->loginOrRegister($googleUser);

        return response()->json([
            'message' => 'Connexion Google réussie',
            'user'    => $result['user'],
            'token'   => $result['token'],
        ]);
    }

    /**
     * =========================
     * AUTH CLASSIQUE (collègue)
     * =========================
     */

    // POST /auth/register
    public function register(Request $request): JsonResponse
    {
        $user = $this->registerMailService->register($request);

        return response()->json([
            'message' => 'Inscription réussie',
            'user'    => $user,
        ], 201);
    }

    // public function login(Request $request) {}
    // public function logout(Request $request) {}
}
