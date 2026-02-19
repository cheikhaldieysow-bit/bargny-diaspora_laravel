<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function findWithRole(int $id): User
    {
        return User::with('role')->findOrFail($id);
    }

}
