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
    public function index()
    {
        $grades = Grade::with('student.user', 'subject')->get();
        return response()->json($grades);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'term' => 'required|string',
            'score' => 'required|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);

        $grade = Grade::create($validated);
        $grade->load('student.user', 'subject');
        
        return response()->json($grade, 201);
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
