<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ProjectSeeder::class,
            ContributionPaymentSeeder::class,
            NewsSeeder::class,
        ]);

        $this->command->info('🎉 Database seeding completed successfully!');
    }
}
