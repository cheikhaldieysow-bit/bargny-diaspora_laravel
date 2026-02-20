<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\Role;
use App\DTO\UserDTO;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Auth\RoleNotFoundException;
use App\Exceptions\Auth\EmailAlreadyUsedException;
use App\Exceptions\Auth\PhoneAlreadyUsedException;

class RegisterMailService
{
    /**
     * Enregistre un utilisateur avec email, téléphone et mot de passe.
     *
     * @param array $data
     * @return UserDTO
     *
     * @throws RoleNotFoundException
     * @throws EmailAlreadyUsedException
     * @throws PhoneAlreadyUsedException
     */
    public function register(array $data): UserDTO
    {
        // Vérifier que le rôle "Owner" existe
        $role = Role::where('name', 'Owner')->first();
        if (!$role) {
            throw new RoleNotFoundException('Le rôle Owner est introuvable.');
        }

        // Vérifier que l'email est unique
        if (User::where('email', $data['email'])->exists()) {
            throw new EmailAlreadyUsedException();
        }

        // Vérifier que le téléphone est unique
        if (User::where('phone', $data['phone'])->exists()) {
            throw new PhoneAlreadyUsedException();
        }

        // Créer l'utilisateur
        $user = User::create([
            'role_id' => $role->id,
            'name'     => $data['name'],
            'email'    => $data['email'],
            'address'  => $data['address'] ?? null,
            'phone'    => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        // Retourner le DTO
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
