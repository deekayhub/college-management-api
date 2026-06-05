<?php

namespace Database\Seeders;

use App\Models\Course;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Student::factory(20)->create();
        Course::factory(20)->create();


        User::factory()->create([
            'name' => 'Deepak kumar',
            'email' => 'deepak@gmail.com',
            'password' => Hash::make('password'),
            
        ]);
    }
}
