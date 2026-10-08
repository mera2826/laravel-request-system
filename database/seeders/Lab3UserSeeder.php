<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Lab3UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'studenta@example.com'],
            [
                'name' => 'Student A',
                'password' => Hash::make('StudentA123!'),
                'is_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['email' => 'studentb@example.com'],
            [
                'name' => 'Student B',
                'password' => Hash::make('StudentB123!'),
                'is_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('Admin123!'),
                'is_admin' => true,
            ]
        );
    }
}