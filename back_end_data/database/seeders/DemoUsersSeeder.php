<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $adminUser = \App\Models\User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Demo Admin',
                'email' => 'admin@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '+1234567890'
            ]
        );

        // Create Teacher User
        $teacherUser = \App\Models\User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Demo Teacher',
                'email' => 'teacher@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('teacher123'),
                'role' => 'teacher',
                'phone' => '+1234567891'
            ]
        );

        // Create Teacher Profile
        \App\Models\Teacher::updateOrCreate(
            ['user_id' => $teacherUser->id],
            [
                'user_id' => $teacherUser->id,
                'employee_id' => 'TCH001',
                'department' => 'Mathematics',
                'specialization' => 'Algebra and Calculus',
                'hire_date' => '2023-01-15',
                'phone' => '+1234567891',
                'address' => '123 Teacher Street, Education City',
                'status' => 'active'
            ]
        );

        // Create Student User
        $studentUser = \App\Models\User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Demo Student',
                'email' => 'student@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('student123'),
                'role' => 'student',
                'phone' => '+1234567892'
            ]
        );

        // Create Student Profile
        \App\Models\Student::updateOrCreate(
            ['user_id' => $studentUser->id],
            [
                'user_id' => $studentUser->id,
                'class' => '2A',
                'dob' => '2005-03-15',
                'gender' => 'male',
                'status' => 'active',
                'address' => '456 Student Avenue, Learning Town',
                'guardian_name' => 'John Smith',
                'guardian_phone' => '+1234567893',
                'guardian_email' => 'john.smith@example.com',
                'guardian_relation' => 'Father'
            ]
        );

        echo "Demo users created successfully!\n";
        echo "Admin: admin@example.com / admin123\n";
        echo "Teacher: teacher@example.com / teacher123\n";
        echo "Student: student@example.com / student123\n";
    }
}
