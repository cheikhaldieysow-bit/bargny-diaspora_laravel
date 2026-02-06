<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as GoogleUser;

class GoogleAuthService
{
    public function loginOrRegister(GoogleUser $googleUser): array
    {
        // 1) Chercher par google_id ou email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        // 2) Créer si absent
        if (!$user) {
            $user = User::create([
                'name'      => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Utilisateur',
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'provider'  => 'google',
                'avatar'    => $googleUser->getAvatar(),
                'password'  => Hash::make(Str::random(32)),
            ]);
        } else {
            // 3) Mettre à jour google_id/provider/avatar si user existait par email
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'provider'  => 'google',
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            }
        }

        // 4) Login + token Sanctum
        Auth::login($user);
        $token = $user->createToken('google-auth')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
