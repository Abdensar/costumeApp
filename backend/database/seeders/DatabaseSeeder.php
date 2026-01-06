<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@costumeapp.com',
            'password' => Hash::make('password123'),
            'phone' => '+1234567890',
            'role' => 'admin',
        ]);

        // Create customer user
        User::create([
            'name' => 'John Doe',
            'email' => 'customer@costumeapp.com',
            'password' => Hash::make('password123'),
            'phone' => '+0987654321',
            'role' => 'customer',
        ]);

        // Seed categories and costumes
        $this->call([
            CategorySeeder::class,
            CostumeSeeder::class,
        ]);
    }
}
