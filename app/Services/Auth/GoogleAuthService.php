<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthService
{
    /**
     * Trouver ou créer un user depuis Google.
     * IMPORTANT: ici tu appelles la logique d'inscription du groupe si besoin.
     */
    public function loginOrRegisterWithGoogle(array $google): array
    {
        // 1) Chercher user existant
        $user = User::where('google_id', $google['google_id'])
            ->orWhere('email', $google['email'])
            ->first();

        // 2) Si pas trouvé => on délègue l'inscription à la méthode "register" du groupe
        if (!$user) {
            // 👉 Remplace cette ligne par L'APPEL DU SERVICE DE TON COLLÈGUE
            // Exemple: $user = $this->registerService->registerFromGoogle($google);

            $user = User::create([
                'name' => $google['name'],
                'email' => $google['email'],
                'google_id' => $google['google_id'],
                'provider' => 'google',
                'avatar' => $google['avatar'] ?? null,
                'password' => bcrypt(\Illuminate\Support\Str::random(32)),
            ]);
        }

        // 3) Login + token (Sanctum)
        Auth::login($user);
        $token = $user->createToken('google-login')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }
}
