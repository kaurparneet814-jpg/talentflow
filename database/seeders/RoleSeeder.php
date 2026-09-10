<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert roles into the roles table
        Role::insert([
            ['name' => 'admin'],
            ['name' => 'recruiter'],
            ['name' => 'candidate'],
        ]);
    }
}
