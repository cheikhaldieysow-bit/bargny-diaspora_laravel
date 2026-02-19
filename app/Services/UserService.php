<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $repository
    ) {}

    public function getUserProfile(int $id): UserDTO
    {
        $user = $this->repository->findWithRole($id);

        return new UserDTO(
            id: $user->id,
            role_id: $user->role_id,
            name: $user->name,
            email: $user->email,
            email_verified_at: $user->email_verified_at?->toDateTimeString(),
            address: $user->address,
            phone: $user->phone,
            created_at: $user->created_at?->toDateTimeString(),
            updated_at: $user->updated_at?->toDateTimeString()
        );
    }
}

