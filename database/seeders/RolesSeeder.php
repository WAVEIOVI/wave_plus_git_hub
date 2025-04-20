<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Admin',
            'Operations Manager',
            'Finance Manager',
            'Technician',
            'Client',
            'Group Client'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
