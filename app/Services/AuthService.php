<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Login avec email ou phone et password
     */
    public function login(string $login, string $password)
    {
        // Cherche l'utilisateur par email ou phone
        $user = User::where('email', $login)
                    ->orWhere('phone', $login)
                    ->first();

        // Vérifie si utilisateur existe et mot de passe correct
        if (!$user || !Hash::check($password, $user->password)) {
            return null; // identifiants invalides
        }

        // Génère un token API si tu utilises Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
