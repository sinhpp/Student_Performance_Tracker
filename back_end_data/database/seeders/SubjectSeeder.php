<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH', 'teacher_name' => 'Dr. Smith'],
            ['name' => 'Science', 'code' => 'SCI', 'teacher_name' => 'Prof. Johnson'],
            ['name' => 'English', 'code' => 'ENG', 'teacher_name' => 'Ms. Williams'],
            ['name' => 'History', 'code' => 'HIST', 'teacher_name' => 'Mr. Brown'],
            ['name' => 'Geography', 'code' => 'GEO', 'teacher_name' => 'Dr. Davis'],
            ['name' => 'Computer Science', 'code' => 'CS', 'teacher_name' => 'Prof. Wilson'],
            ['name' => 'Art', 'code' => 'ART', 'teacher_name' => 'Ms. Taylor'],
            ['name' => 'Physical Education', 'code' => 'PE', 'teacher_name' => 'Coach Miller'],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
                ['code' => $subject['code']],
                $subject
            );
        }
    }
}
