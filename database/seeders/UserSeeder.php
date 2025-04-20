<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create test user
        $user = User::create([
            'name' => 'Wave',
            'email' => 'wave@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
