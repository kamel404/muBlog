<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => '10121641@mu.edu.lb',
            'password' => Hash::make('password'),
            'role_id' => 2, 
        ]);

        // Optional: Add a regular user
        User::create([
            'name' => 'Regular User',
            'username' => 'user',
            'email' => '10121642@mu.edu.lb',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);
    }
}
