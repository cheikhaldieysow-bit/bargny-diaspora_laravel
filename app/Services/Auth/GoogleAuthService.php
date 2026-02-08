<?php

namespace App\Services\Auth;

use App\Exceptions\Auth\EmailAlreadyUsedException;
use App\Exceptions\Auth\GoogleAccountNotFoundException;
use App\Exceptions\Auth\GoogleEmailMissingException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleAuthService
{
    public function login(array $googleUser): array
    {
        $email = $googleUser['email'] ?? null;
        if (!$email) {
            throw new GoogleEmailMissingException();
        }

        $user = User::query()
            ->where('google_id', $googleUser['sub'])
            ->orWhere('email', $email)
            ->first();

        if (!$user) {
            throw new GoogleAccountNotFoundException();
        }

        $this->syncGoogleData($user, $googleUser);

        return $this->authenticate($user, 'google-login');
    }

    public function register(array $googleUser): array
    {
        $email = $googleUser['email'] ?? null;
        if (!$email) {
            throw new GoogleEmailMissingException();
        }

        $existing = User::query()->where('email', $email)->first();
        if ($existing) {
            throw new EmailAlreadyUsedException();
        }

        $user = User::create([
            'name'      => $googleUser['name'] ?? 'Utilisateur',
            'email'     => $email,
            'google_id' => $googleUser['sub'],
            'provider'  => 'google',
            'avatar'    => $googleUser['picture'] ?? null,
            'password'  => Hash::make(Str::random(32)),
        ]);

        return $this->authenticate($user, 'google-register');
    }

    private function authenticate(User $user, string $tokenName): array
    {
        Auth::login($user);

        return [
            'user'  => $user,
            'token' => $user->createToken($tokenName)->plainTextToken,
        ];
    }

    private function syncGoogleData(User $user, array $googleUser): void
    {
        // Sync si google_id absent ou si l'utilisateur existait déjà par email
        $updates = [];

        if (!$user->google_id) {
            $updates['google_id'] = $googleUser['sub'] ?? null;
            $updates['provider']  = 'google';
        }

        $avatar = $googleUser['picture'] ?? null;
        if ($avatar && $user->avatar !== $avatar) {
            $updates['avatar'] = $avatar;
        }

        if (!empty($updates)) {
            $user->update($updates);
        }
    }
}
