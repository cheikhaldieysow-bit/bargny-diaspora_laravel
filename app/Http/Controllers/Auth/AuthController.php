<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\GoogleAuthService;
use App\Services\Auth\RegisterMailService;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterWithRequest;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Contracts\Factory as Socialite;

class AuthController extends Controller
{
    /**
     * =========================
     * GOOGLE AUTH (ta partie)
     * =========================
     */

      public function redirect()

        {
        return Socialite::driver('google')->redirect();
    }

    public function callback(RegisterGoogleService $registerGoogleService): JsonResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = $registerGoogleService->registerOrLogin($googleUser);

        // Créer token Sanctum
        $token = $user->createToken('google-auth')->plainTextToken;

        return response()->json([
            'message' => 'Connexion Google réussie',
            'token' => $token,
            'user' => $user,
        ]);
    }

    // 🔁 REDIRECT GOOGLE
    public function googleRedirect(Socialite $socialite)
    {
        return $socialite->driver('google')->stateless()->redirect();
    }

    // 🔁 CALLBACK GOOGLE
    public function googleCallback(
        Socialite $socialite,
        RegisterGoogleService $registerGoogleService
    ) {
        $googleUser = $socialite->driver('google')->stateless()->user();

        if (!$googleUser->getEmail()) {
            return response()->json([
                'message' => "Google n'a pas fourni d'email."
            ], 422);
        }

        $result = $registerGoogleService->loginOrRegisterWithGoogle([
            'name' => $googleUser->getName()
                ?? $googleUser->getNickname()
                ?? 'Utilisateur',
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
        ]);

        return response()->json($result);
    }

    /**
     * =========================
     * AUTH CLASSIQUE
     * (gérée par tes camarades)
     * =========================
     */

     protected RegisterMailService $registerMailService;

    public function __construct(RegisterMailService $registerMailService)
    {
        $this->registerMailService = $registerMailService;
    }
     public function register(RegisterWithRequest $request): JsonResponse
    {
        try {
            $user = $this->registerMailService->register($request);

            return response()->json([
                'message' => 'Inscription réussie',
                'user' => $user,
                //'token' => $user->createToken('auth_token')->plainTextToken,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l’inscription',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
    // public function login(Request $request) {}
}
