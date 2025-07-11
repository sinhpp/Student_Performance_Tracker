<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+1234567890',
                'class' => 'Grade 5',
                'dob' => '2010-05-15',
                'gender' => 'male',
                'status' => 'active',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '+1234567891',
                'class' => 'Grade 4',
                'dob' => '2011-08-22',
                'gender' => 'female',
                'status' => 'active',
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob.johnson@example.com',
                'phone' => '+1234567892',
                'class' => 'Grade 5',
                'dob' => '2010-03-10',
                'gender' => 'male',
                'status' => 'active',
            ],
            [
                'name' => 'Alice Brown',
                'email' => 'alice.brown@example.com',
                'phone' => '+1234567893',
                'class' => 'Grade 6',
                'dob' => '2009-12-05',
                'gender' => 'female',
                'status' => 'active',
            ],
            [
                'name' => 'Charlie Wilson',
                'email' => 'charlie.wilson@example.com',
                'phone' => '+1234567894',
                'class' => 'Grade 4',
                'dob' => '2011-09-30',
                'gender' => 'male',
                'status' => 'active',
            ],
        ];

        foreach ($students as $studentData) {
            $user = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'phone' => $studentData['phone'],
                    'password' => bcrypt('password'),
                    'role' => 'student',
                ]
            );

            Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'class' => $studentData['class'],
                    'dob' => $studentData['dob'],
                    'gender' => $studentData['gender'],
                    'status' => $studentData['status'],
                ]
            );
        }
    }
}
