<?php

namespace Database\Seeders;

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
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@bank.com',
            'password' => Hash::make('password'),
            'balance' => 10000.00,
            'profile_data' => [
                'phone' => '+1234567890',
                'address' => '123 Admin Street',
                'city' => 'Admin City',
                'state' => 'AC',
                'zip' => '12345',
                'date_of_birth' => '1990-01-01',
            ],
        ]);
        $admin->assignRole('admin');

        // Create manager user
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@bank.com',
            'password' => Hash::make('password'),
            'balance' => 5000.00,
            'profile_data' => [
                'phone' => '+1234567891',
                'address' => '456 Manager Avenue',
                'city' => 'Manager City',
                'state' => 'MC',
                'zip' => '12346',
                'date_of_birth' => '1985-05-15',
            ],
        ]);
        $manager->assignRole('manager');

        // Create regular users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'balance' => 2500.00,
                'profile_data' => [
                    'phone' => '+1234567892',
                    'address' => '789 User Street',
                    'city' => 'User City',
                    'state' => 'UC',
                    'zip' => '12347',
                    'date_of_birth' => '1992-03-20',
                ],
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'balance' => 1800.00,
                'profile_data' => [
                    'phone' => '+1234567893',
                    'address' => '321 Customer Lane',
                    'city' => 'Customer City',
                    'state' => 'CC',
                    'zip' => '12348',
                    'date_of_birth' => '1988-07-10',
                ],
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'balance' => 3200.00,
                'profile_data' => [
                    'phone' => '+1234567894',
                    'address' => '654 Client Road',
                    'city' => 'Client City',
                    'state' => 'CC',
                    'zip' => '12349',
                    'date_of_birth' => '1995-11-25',
                ],
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'balance' => $userData['balance'],
                'profile_data' => $userData['profile_data'],
            ]);
            $user->assignRole('user');
        }
    }
}
