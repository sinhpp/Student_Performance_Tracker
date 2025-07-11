<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Term;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $terms = [
            [
                'name' => 'Term 1',
                'start_date' => '2025-01-01',
                'end_date' => '2025-04-30',
                'is_active' => true
            ],
            [
                'name' => 'Term 2',
                'start_date' => '2025-05-01',
                'end_date' => '2025-08-31',
                'is_active' => false
            ],
            [
                'name' => 'Term 3',
                'start_date' => '2025-09-01',
                'end_date' => '2025-12-31',
                'is_active' => false
            ],
        ];

        foreach ($terms as $term) {
            Term::firstOrCreate(
                ['name' => $term['name']],
                $term
            );
        }
    }
}
