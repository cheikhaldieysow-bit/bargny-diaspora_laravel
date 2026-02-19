<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $userRole = Role::where('name', 'User')->first();

        // Create Admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrateur',
                'role_id' => $adminRole->id,
                'password' => Hash::make('password'),
                'phone' => '+229 97 00 00 01',
                'address' => 'Cotonou, Bénin',
                'email_verified_at' => now(),
            ]
        );

        // Create Manager user
        User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager Test',
                'role_id' => $managerRole->id,
                'password' => Hash::make('password'),
                'phone' => '+229 97 00 00 02',
                'address' => 'Abomey-Calavi, Bénin',
                'email_verified_at' => now(),
            ]
        );

        // Create regular users
        $users = [
            [
                'name' => 'Jean Dupont',
                'email' => 'jean.dupont@example.com',
                'phone' => '+229 97 11 22 33',
                'address' => 'Porto-Novo, Bénin',
            ],
            [
                'name' => 'Marie Kouassi',
                'email' => 'marie.kouassi@example.com',
                'phone' => '+229 97 44 55 66',
                'address' => 'Parakou, Bénin',
            ],
            [
                'name' => 'Paul Agbessi',
                'email' => 'paul.agbessi@example.com',
                'phone' => '+229 97 77 88 99',
                'address' => 'Bohicon, Bénin',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'role_id' => $userRole->id,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
            );
        }

        $this->command->info('Users created successfully!');
        $this->command->info('Default password for all users: password');
    }
}
