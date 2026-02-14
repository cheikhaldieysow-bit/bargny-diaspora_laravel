<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\ViewProfileRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function viewProfile(ViewProfileRequest $request): JsonResponse
    {
        $userDTO = $this->userService->getUserProfile(
            $request->validated()['id']
        );

        return response()->json($userDTO);
    }
}

