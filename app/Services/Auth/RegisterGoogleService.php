<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as GoogleUser;

class GoogleAuthService
{
    public function registerOrLogin(GoogleUser $googleUser): User
    {
        // 1. Chercher par google_id ou email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        // 2. Créer l’utilisateur s’il n’existe pas
        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(32)),
            ]);
        }

        // 3. Mettre à jour google_id si user existait par email
        if (!$user->google_id) {
            $user->update([
                'google_id' => $googleUser->getId(),
            ]);
        }

        return $user;
    }
}
