<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Grade::with('student.user', 'subject');
        
        // Apply filters
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        
        if ($request->filled('term_id')) {
            $termNames = ['1' => 'Term 1', '2' => 'Term 2', '3' => 'Term 3'];
            $termName = $termNames[$request->term_id] ?? null;
            if ($termName) {
                $query->where('term', $termName);
            }
        }
        
        if ($request->filled('class_id')) {
            $className = $this->getClassNameById($request->class_id);
            if ($className) {
                $query->whereHas('student', function ($q) use ($className) {
                    $q->where('class', $className);
                });
            }
        }
        
        // Add search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('student', function ($q) use ($search) {
                // Search by student ID pattern (STU001, STU002, etc.)
                if (preg_match('/^STU(\d+)$/i', $search, $matches)) {
                    $studentId = (int)$matches[1];
                    $q->where('id', $studentId);
                } else {
                    // Search by just the number part
                    $q->where('id', $search);
                }
            });
        }
        
        $grades = $query->get()->map(function ($grade) {
            return [
                'id' => $grade->id,
                'student' => [
                    'id' => $grade->student->id,
                    'name' => $grade->student->user->name,
                    'student_id' => 'STU' . str_pad($grade->student->id, 3, '0', STR_PAD_LEFT),
                ],
                'subject' => [
                    'id' => $grade->subject->id,
                    'name' => $grade->subject->name,
                ],
                'term' => [
                    'id' => 1, // Since we're using string terms, we'll mock the ID
                    'name' => $grade->term,
                ],
                'score' => $grade->score,
                'letter_grade' => $this->calculateLetterGrade($grade->score),
                'remarks' => $grade->remarks,
                'created_at' => $grade->created_at->format('Y-m-d'),
                'updated_at' => $grade->updated_at->format('Y-m-d'),
            ];
        });
        
        return response()->json([
            'data' => $grades,
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => 50,
                'total' => $grades->count()
            ]
        ]);
    }

    private function calculateLetterGrade($score)
    {
        if ($score >= 90) return 'A+';
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 60) return 'C';
        if ($score >= 50) return 'D';
        return 'F';
    }

    private function getClassNameById($classId)
    {
        $classes = [
            '1' => 'Grade 1',
            '2' => 'Grade 2', 
            '3' => 'Grade 3',
            '4' => 'Grade 4',
            '5' => 'Grade 5',
        ];
        
        return $classes[$classId] ?? null;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'term_id' => 'required', // Handle term_id from frontend
            'score' => 'required|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);

        // Convert term_id to term name
        $termNames = ['1' => 'Term 1', '2' => 'Term 2', '3' => 'Term 3'];
        $termName = $termNames[$validated['term_id']] ?? 'Term 1';

        $gradeData = [
            'student_id' => $validated['student_id'],
            'subject_id' => $validated['subject_id'],
            'term' => $termName,
            'score' => $validated['score'],
            'remarks' => $validated['remarks'] ?? null,
        ];

        $grade = Grade::create($gradeData);
        $grade->load('student.user', 'subject');
        
        return response()->json([
            'id' => $grade->id,
            'student' => [
                'id' => $grade->student->id,
                'name' => $grade->student->user->name,
                'student_id' => 'STU' . str_pad($grade->student->id, 3, '0', STR_PAD_LEFT),
            ],
            'subject' => [
                'id' => $grade->subject->id,
                'name' => $grade->subject->name,
            ],
            'term' => [
                'id' => $validated['term_id'],
                'name' => $termName,
            ],
            'score' => $grade->score,
            'letter_grade' => $this->calculateLetterGrade($grade->score),
            'remarks' => $grade->remarks,
            'created_at' => $grade->created_at->format('Y-m-d'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        $grade->load('student.user', 'subject');
        return response()->json($grade);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'student_id' => 'sometimes|exists:students,id',
            'subject_id' => 'sometimes|exists:subjects,id',
            'term' => 'sometimes|string',
            'score' => 'sometimes|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);

        $grade->update($validated);
        $grade->load('student.user', 'subject');
        
        return response()->json($grade);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();
        return response()->json(['message' => 'Grade deleted successfully']);
    }

    /**
     * Get grade statistics.
     */
    public function statistics(Request $request)
    {
        $query = Grade::query();

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->has('term')) {
            $query->where('term', $request->term);
        }

        $stats = [
            'total_grades' => $query->count(),
            'average_score' => $query->avg('score'),
            'highest_score' => $query->max('score'),
            'lowest_score' => $query->min('score'),
            'grade_distribution' => [
                'A' => $query->where('letter_grade', 'A')->count(),
                'B' => $query->where('letter_grade', 'B')->count(),
                'C' => $query->where('letter_grade', 'C')->count(),
                'D' => $query->where('letter_grade', 'D')->count(),
                'F' => $query->where('letter_grade', 'F')->count(),
            ]
        ];

        return response()->json($stats);
    }
}
