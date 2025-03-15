<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@123.com'], 
            [
                'role' => 'Manager',
                'name' => 'Admin',
                'email' => 'admin@123.com',
                'password' => bcrypt('admin123'), 
            ]
        );
    }
}
