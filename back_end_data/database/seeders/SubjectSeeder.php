<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH001', 'teacher_name' => 'Dr. Smith'],
            ['name' => 'English', 'code' => 'ENG001', 'teacher_name' => 'Ms. Johnson'],
            ['name' => 'Science', 'code' => 'SCI001', 'teacher_name' => 'Mr. Davis'],
            ['name' => 'History', 'code' => 'HIST001', 'teacher_name' => 'Mrs. Brown'],
            ['name' => 'Physical Education', 'code' => 'PE001', 'teacher_name' => 'Coach Wilson'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
