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
        $studentUser = User::where('role', 'student')->first();
        
        if ($studentUser) {
            Student::create([
                'user_id' => $studentUser->id,
                'class' => 'Grade 10A',
                'dob' => '2008-05-15',
                'gender' => 'male',
                'status' => 'active',
            ]);
        }
    }
}
