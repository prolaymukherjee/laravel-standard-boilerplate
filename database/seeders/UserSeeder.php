<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'John Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('AdmiN@987'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Jame Smith',
            'email' => 'jame@example.com',
            'password' => Hash::make('JamE@987'),
            'role' => 'author',
        ]);
    }
}
