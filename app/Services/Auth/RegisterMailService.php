<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegisterMailService
{
     public function register(RegisterWithRequest $request): User
    {
        try {
            $role = Role::where('name', 'Owner')->firstOrFail();

            return User::create([
                'role_id' => $role->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

        } catch (ModelNotFoundException $e) {
            throw new \Exception('Le rôle Owner est introuvable.');
        }
    }
}
