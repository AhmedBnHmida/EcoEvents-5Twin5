<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'first_name' => 'Melek',
            'last_name' => 'Guesmi',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => 'admin'
        ]);
    }
}
