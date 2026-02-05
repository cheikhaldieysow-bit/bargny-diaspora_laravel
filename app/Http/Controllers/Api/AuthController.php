<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login($request->login, $request->password);

        if (!$result) {
            return response()->json([
                'message' => 'Identifiants invalides'
            ], 401);
        }

        return response()->json([
            'message' => 'Connexion réussie',
            'access_token' => $result['token'],
            'token_type' => 'Bearer',
            'user' => $result['user']
        ]);
    }
}
