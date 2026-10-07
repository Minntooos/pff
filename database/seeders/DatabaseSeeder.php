<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'id' => 1,
            'usertype' => 'user',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'profile_picture' => 'assets/images/default-avatar.jpeg',

            'password' => bcrypt('pass123'),
        ]);
        User::factory()->create([
            'id' => 2,
            'usertype' => 'admin',
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
        ]);

    }
}
