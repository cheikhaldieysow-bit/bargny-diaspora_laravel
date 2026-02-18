<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleLoginRequest;
use App\Http\Requests\Auth\GoogleRegisterRequest;
use App\Services\Auth\GoogleAuthService;
use App\Services\Auth\GoogleTokenVerifier;
use Illuminate\Http\JsonResponse;

class GoogleAuthController extends Controller
{
    public function login(
        GoogleLoginRequest $request,
        GoogleTokenVerifier $verifier,
        GoogleAuthService $service
    ): JsonResponse {
        $googleUser = $verifier->verify($request->validated()['id_token']);
        $result = $service->login($googleUser);

        return response()->json([
            'message' => 'Connexion Google réussie',
            'user'    => $result['user'],
            'token'   => $result['token'],
        ]);
    }

    public function register(
        GoogleRegisterRequest $request,
        GoogleTokenVerifier $verifier,
        GoogleAuthService $service
    ): JsonResponse {
        $googleUser = $verifier->verify($request->validated()['id_token']);
        $result = $service->register($googleUser);

        return response()->json([
            'message' => 'Inscription Google réussie',
            'user'    => $result['user'],
            'token'   => $result['token'],
        ], 201);
    }
}
