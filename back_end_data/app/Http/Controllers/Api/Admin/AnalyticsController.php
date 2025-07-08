<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function overview()
    {
        $totalStudents = Student::count();
        $totalSubjects = Subject::count();
        
        $averageGrade = Grade::avg('score');
        
        $attendanceStats = Attendance::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        $totalAttendanceRecords = Attendance::count();
        $presentCount = $attendanceStats['present'] ?? 0;
        $attendanceRate = $totalAttendanceRecords > 0 ? ($presentCount / $totalAttendanceRecords) * 100 : 0;
        
        $topPerformers = Student::with('user')
            ->withAvg('grades', 'score')
            ->having('grades_avg_score', '>', 80)
            ->orderBy('grades_avg_score', 'desc')
            ->take(5)
            ->get();
        
        $subjectPerformance = Subject::with(['grades' => function($query) {
            $query->selectRaw('subject_id, AVG(score) as avg_score');
        }])
        ->withAvg('grades', 'score')
        ->get();
        
        return response()->json([
            'total_students' => $totalStudents,
            'total_subjects' => $totalSubjects,
            'average_grade' => round($averageGrade, 2),
            'attendance_rate' => round($attendanceRate, 2),
            'attendance_stats' => $attendanceStats,
            'top_performers' => $topPerformers,
            'subject_performance' => $subjectPerformance,
        ]);
    }
}
