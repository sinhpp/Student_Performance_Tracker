<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Student;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $students = Student::all();
        
        if ($students->count() > 0) {
            $dates = [
                '2025-07-08',
                '2025-07-09', 
                '2025-07-10'
            ];
            
            $statuses = ['present', 'absent', 'late'];
            
            foreach ($students as $student) {
                foreach ($dates as $date) {
                    Attendance::firstOrCreate([
                        'student_id' => $student->id,
                        'date' => $date,
                    ], [
                        'status' => $statuses[array_rand($statuses)]
                    ]);
                }
            }
        }
    }
}
