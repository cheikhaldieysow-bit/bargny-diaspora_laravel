<?php

namespace Database\Seeders;

use App\Models\ContributionPayment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContributionPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $paymentMethods = ['cash', 'bank_transfer', 'mobile_money', 'credit_card'];
        $amounts = [5000, 10000, 15000, 20000, 25000, 50000];

        // Create 20 random payments
        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            $isPaid = rand(0, 100) > 20; // 80% chance of being paid

            ContributionPayment::create([
                'user_id' => $user->id,
                'amount' => $amounts[array_rand($amounts)],
                'paid_at' => $isPaid ? now()->subDays(rand(1, 90)) : null,
                'payment_method' => $isPaid ? $paymentMethods[array_rand($paymentMethods)] : null,
            ]);
        }

        // Create specific payments for demonstration
        $specificPayments = [
            [
                'amount' => 100000,
                'paid_at' => now()->subDays(5),
                'payment_method' => 'bank_transfer',
            ],
            [
                'amount' => 75000,
                'paid_at' => now()->subDays(15),
                'payment_method' => 'mobile_money',
            ],
            [
                'amount' => 50000,
                'paid_at' => null,
                'payment_method' => null,
            ],
        ];

        foreach ($specificPayments as $payment) {
            ContributionPayment::create(array_merge($payment, [
                'user_id' => $users->random()->id,
            ]));
        }

        $this->command->info('Contribution payments created successfully!');
    }
}
