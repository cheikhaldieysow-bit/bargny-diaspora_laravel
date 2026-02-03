<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\GoogleAuthService;
use Laravel\Socialite\Contracts\Factory as Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(Socialite $socialite)
    {
        return $socialite->driver('google')->stateless()->redirect();
    }

    public function callback(Socialite $socialite, GoogleAuthService $authService)
    {
        $googleUser = $socialite->driver('google')->stateless()->user();

        if (!$googleUser->getEmail()) {
            return response()->json(['message' => "Google n'a pas fourni d'email."], 422);
        }

        $result = $authService->loginOrRegisterWithGoogle([
            'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Utilisateur',
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
        ]);

        return response()->json($result);
    }
}
