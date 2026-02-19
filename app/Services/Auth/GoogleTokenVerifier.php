<?php

namespace App\Services\Auth;

use Google\Client as GoogleClient;
use Exception;

class GoogleTokenVerifier
{
    public function verify(string $idToken): array
    {
        $client = new GoogleClient([
            'client_id' => config('services.google.client_id'),
        ]);

        $payload = $client->verifyIdToken($idToken);

        if (!$payload) {
            throw new Exception('Token Google invalide');
        }

        return $payload;
    }
}
