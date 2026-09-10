<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles first
        $this->call([
            RoleSeeder::class,
        ]);

        // Get Recruiter role
        $recruiterRole = Role::where('name', 'Recruiter')->first();

        // Create test recruiter
        User::create([
            'name' => 'Test Recruiter',
            'email' => 'test@example.com',
            'password' => 'password',
            'role_id' => $recruiterRole->id,
        ]);
    }
}