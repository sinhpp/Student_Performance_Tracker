<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $students = Student::with('user')->get();
        $subjects = Subject::all();
        
        if ($students->count() > 0 && $subjects->count() > 0) {
            $terms = ['Term 1', 'Term 2', 'Term 3'];
            
            foreach ($students as $student) {
                foreach ($subjects->take(3) as $subject) { // Only 3 subjects per student
                    foreach ($terms as $term) {
                        Grade::firstOrCreate([
                            'student_id' => $student->id,
                            'subject_id' => $subject->id,
                            'term' => $term,
                        ], [
                            'score' => rand(60, 95), // Random score between 60-95
                            'remarks' => 'Good performance',
                        ]);
                    }
                }
            }
        }
    }
}
