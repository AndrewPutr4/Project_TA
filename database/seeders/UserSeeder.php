<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'andrew',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'created_at' => '2025-06-22 01:48:27',
            'updated_at' => '2025-06-22 01:48:27',
        ]);

        User::create([
            'name' => 'kasir123',
            'email' => 'kasir123@gmail.com',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
            'created_at' => '2025-06-25 06:11:43',
            'updated_at' => '2025-06-25 06:11:43',
        ]);
    }
}
