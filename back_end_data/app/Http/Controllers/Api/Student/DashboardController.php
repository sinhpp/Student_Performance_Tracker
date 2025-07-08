<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function myGrades(Request $request)
    {
        $student = $request->user()->student;
        
        if (!$student) {
            return response()->json(['message' => 'Student profile not found'], 404);
        }
        
        $grades = $student->grades()->with('subject')->get();
        
        return response()->json($grades);
    }
    
    public function myAttendance(Request $request)
    {
        $student = $request->user()->student;
        
        if (!$student) {
            return response()->json(['message' => 'Student profile not found'], 404);
        }
        
        $attendance = $student->attendance()->orderBy('date', 'desc')->get();
        
        return response()->json($attendance);
    }
    
    public function myFeedback(Request $request)
    {
        $student = $request->user()->student;
        
        if (!$student) {
            return response()->json(['message' => 'Student profile not found'], 404);
        }
        
        $feedback = $student->feedbacks()->with('creator')->orderBy('date', 'desc')->get();
        
        return response()->json($feedback);
    }
}
