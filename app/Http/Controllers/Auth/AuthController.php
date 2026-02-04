<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\GoogleAuthService;
use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\Factory as Socialite;

class AuthController extends Controller
{
    /**
     * =========================
     * GOOGLE AUTH (ta partie)
     * =========================
     */

    // 🔁 REDIRECT GOOGLE
    public function googleRedirect(Socialite $socialite)
    {
        return $socialite->driver('google')->stateless()->redirect();
    }

    // 🔁 CALLBACK GOOGLE
    public function googleCallback(
        Socialite $socialite,
        GoogleAuthService $googleAuthService
    ) {
        $googleUser = $socialite->driver('google')->stateless()->user();

        if (!$googleUser->getEmail()) {
            return response()->json([
                'message' => "Google n'a pas fourni d'email."
            ], 422);
        }

        $result = $googleAuthService->loginOrRegisterWithGoogle([
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
    // public function register(Request $request) {}
    // public function login(Request $request) {}
}
