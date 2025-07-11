<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolClass;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            // Grade 1
            ['name' => '1A', 'grade_level' => 'Grade 1'],
            ['name' => '1B', 'grade_level' => 'Grade 1'],
            ['name' => '1C', 'grade_level' => 'Grade 1'],
            
            // Grade 2
            ['name' => '2A', 'grade_level' => 'Grade 2'],
            ['name' => '2B', 'grade_level' => 'Grade 2'],
            ['name' => '2C', 'grade_level' => 'Grade 2'],
            
            // Grade 3
            ['name' => '3A', 'grade_level' => 'Grade 3'],
            ['name' => '3B', 'grade_level' => 'Grade 3'],
            ['name' => '3C', 'grade_level' => 'Grade 3'],
            
            // Grade 4
            ['name' => '4A', 'grade_level' => 'Grade 4'],
            ['name' => '4B', 'grade_level' => 'Grade 4'],
            ['name' => '4C', 'grade_level' => 'Grade 4'],
            
            // Grade 5
            ['name' => '5A', 'grade_level' => 'Grade 5'],
            ['name' => '5B', 'grade_level' => 'Grade 5'],
            ['name' => '5C', 'grade_level' => 'Grade 5'],
            
            // Grade 6
            ['name' => '6A', 'grade_level' => 'Grade 6'],
            ['name' => '6B', 'grade_level' => 'Grade 6'],
            ['name' => '6C', 'grade_level' => 'Grade 6'],
        ];

        foreach ($classes as $class) {
            SchoolClass::updateOrCreate(
                ['name' => $class['name']],
                $class
            );
        }

        echo "School classes seeded successfully!\n";
    }
}
