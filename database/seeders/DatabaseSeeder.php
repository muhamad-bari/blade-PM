<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password8899'),
            'role' => 'admin',
        ]);

        // Guest User
        \App\Models\User::factory()->create([
            'name' => 'Guest User',
            'email' => 'guest@user.com',
            'password' => bcrypt('user123'),
            'role' => 'guest',
        ]);

        // Employees
        $employees = \App\Models\Employee::factory(5)->create();

        // Projects
        \App\Models\Project::factory(3)->create([
            'leader_employee_id' => $employees->first()->id,
        ]);
    }
}
