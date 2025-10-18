<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Admin users
            [
                'name' => 'Admin User',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@ecoevent.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'System Admin',
                'first_name' => 'System',
                'last_name' => 'Admin',
                'email' => 'system@ecoevent.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],

            // Organizer users
            [
                'name' => 'Event Organizer',
                'first_name' => 'Event',
                'last_name' => 'Organizer',
                'email' => 'organizer@ecoevent.com',
                'password' => Hash::make('password'),
                'role' => 'organisateur',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Conference Manager',
                'first_name' => 'Conference',
                'last_name' => 'Manager',
                'email' => 'manager@ecoevent.com',
                'password' => Hash::make('password'),
                'role' => 'organisateur',
                'email_verified_at' => now(),
            ],

            // Supplier users
            [
                'name' => 'Tech Supplies Co',
                'first_name' => 'Tech',
                'last_name' => 'Supplies',
                'email' => 'supplier@tech.com',
                'password' => Hash::make('password'),
                'role' => 'fournisseur',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Food Catering Ltd',
                'first_name' => 'Food',
                'last_name' => 'Catering',
                'email' => 'catering@food.com',
                'password' => Hash::make('password'),
                'role' => 'fournisseur',
                'email_verified_at' => now(),
            ],

            // Participant users
            [
                'name' => 'John Participant',
                'first_name' => 'John',
                'last_name' => 'Participant',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'participant',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sarah Attendee',
                'first_name' => 'Sarah',
                'last_name' => 'Attendee',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
                'role' => 'participant',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mike Visitor',
                'first_name' => 'Mike',
                'last_name' => 'Visitor',
                'email' => 'mike@example.com',
                'password' => Hash::make('password'),
                'role' => 'participant',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Emma Guest',
                'first_name' => 'Emma',
                'last_name' => 'Guest',
                'email' => 'emma@example.com',
                'password' => Hash::make('password'),
                'role' => 'participant',
                'email_verified_at' => now(),
            ],

            // Regular users
            [
                'name' => 'Regular User',
                'first_name' => 'Regular',
                'last_name' => 'User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'role' => 'utilisateur',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Create additional random participants
        //User::factory()->count(20)->create([
        //    'role' => 'participant'
        //]);
    }
}