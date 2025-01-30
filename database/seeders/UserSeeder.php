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
            'role_id' => 1,
        ]);
    }
}
