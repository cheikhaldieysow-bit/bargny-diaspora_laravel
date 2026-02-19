<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'Manager'],
            ['name' => 'User'],
            ['name' => 'Guest'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }

        $this->command->info('Roles created successfully!');
    }
}
