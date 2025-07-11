<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceHistory;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendance = Attendance::with('student.user')->get();
        return response()->json($attendance);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
        ]);

        $attendance = Attendance::create($validated);
        $attendance->load('student.user');
        
        return response()->json($attendance, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        $attendance->load('student.user');
        return response()->json($attendance);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'student_id' => 'sometimes|exists:students,id',
            'date' => 'sometimes|date',
            'status' => 'sometimes|in:present,absent,late,excused',
        ]);

        $attendance->update($validated);
        $attendance->load('student.user');
        
        return response()->json($attendance);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return response()->json(['message' => 'Attendance record deleted successfully']);
    }

    /**
     * Bulk store attendance records.
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.date' => 'required|date',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'class' => 'nullable|string',
            'save_to_history' => 'boolean'
        ]);

        $attendanceRecords = [];
        foreach ($validated['attendances'] as $attendanceData) {
            $attendance = Attendance::updateOrCreate(
                [
                    'student_id' => $attendanceData['student_id'],
                    'date' => $attendanceData['date']
                ],
                ['status' => $attendanceData['status']]
            );
            $attendance->load('student.user');
            $attendanceRecords[] = $attendance;
        }

        // Save to history if requested
        if ($request->get('save_to_history', false)) {
            $this->saveAttendanceHistory($validated['attendances'], $validated['class'] ?? null, 'manual');
        }

        return response()->json([
            'data' => $attendanceRecords,
            'message' => 'Attendance saved successfully!'
        ], 201);
    }

    /**
     * Get attendance for a specific date and class
     */
    public function getByDateAndClass(Request $request)
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'class' => 'nullable|string'
        ]);

        if (!empty($validated['date'])) {
            // If date is provided, show attendance for that specific date
            $query = Attendance::with('student.user')
                ->where('date', $validated['date']);

            if (!empty($validated['class'])) {
                $query->whereHas('student', function ($q) use ($validated) {
                    $q->where('class', $validated['class']);
                });
            }

            $attendances = $query->get();

            // Get all students for the class
            $studentQuery = Student::with('user');
            if (!empty($validated['class'])) {
                $studentQuery->where('class', $validated['class']);
            }
            $allStudents = $studentQuery->get();

            // Merge with attendance data
            $studentsWithAttendance = $allStudents->map(function ($student) use ($attendances, $validated) {
                $attendance = $attendances->firstWhere('student_id', $student->id);
                
                return [
                    'id' => $student->id,
                    'name' => $student->user->name,
                    'student_id' => 'STU' . str_pad($student->id, 3, '0', STR_PAD_LEFT),
                    'class' => $student->class,
                    'status' => $attendance ? $attendance->status : 'present', // Default to present
                    'date' => $validated['date'],
                    'attendance_date' => $validated['date']
                ];
            });
        } else {
            // If no date is provided, show all attendance records
            $query = Attendance::with('student.user');

            if (!empty($validated['class'])) {
                $query->whereHas('student', function ($q) use ($validated) {
                    $q->where('class', $validated['class']);
                });
            }

            $attendances = $query->orderBy('date', 'desc')->get();

            // Group attendance by student and show latest record per student
            $studentsWithAttendance = $attendances->groupBy('student_id')->map(function ($studentAttendances) {
                $latestAttendance = $studentAttendances->first();
                
                return [
                    'id' => $latestAttendance->student->id,
                    'name' => $latestAttendance->student->user->name,
                    'student_id' => 'STU' . str_pad($latestAttendance->student->id, 3, '0', STR_PAD_LEFT),
                    'class' => $latestAttendance->student->class,
                    'status' => $latestAttendance->status,
                    'date' => $latestAttendance->date,
                    'attendance_date' => $latestAttendance->date
                ];
            })->values();
        }

        return response()->json([
            'data' => $studentsWithAttendance,
            'meta' => [
                'date' => $validated['date'] ?? null,
                'class' => $validated['class'] ?? null,
                'total_students' => $studentsWithAttendance->count(),
                'showing_all_records' => empty($validated['date'])
            ]
        ]);
    }

    /**
     * Save attendance to history
     */
    private function saveAttendanceHistory($attendances, $class = null, $saveType = 'manual')
    {
        $dates = collect($attendances)->pluck('date')->unique();
        $startDate = $dates->min();
        $endDate = $dates->max();
        
        // Determine current term based on date
        $currentTerm = $this->getCurrentTerm($startDate);

        AttendanceHistory::create([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'class' => $class,
            'term' => $currentTerm,
            'attendance_data' => $attendances,
            'save_type' => $saveType,
            'created_by' => Auth::id()
        ]);
    }

    /**
     * Get current term based on date
     */
    private function getCurrentTerm($date)
    {
        $month = date('n', strtotime($date));
        
        if ($month >= 1 && $month <= 4) {
            return 'Term 1';
        } elseif ($month >= 5 && $month <= 8) {
            return 'Term 2';
        } else {
            return 'Term 3';
        }
    }

    /**
     * Get attendance statistics.
     */
    public function stats(Request $request)
    {
        try {
            $query = Attendance::query();

            if ($request->has('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->where('date', '<=', $request->date_to);
            }

            $totalRecords = $query->count();
            $presentCount = (clone $query)->where('status', 'present')->count();
            $absentCount = (clone $query)->where('status', 'absent')->count();
            $lateCount = (clone $query)->where('status', 'late')->count();
            $excusedCount = (clone $query)->where('status', 'excused')->count();

            $stats = [
                'total_records' => $totalRecords,
                'present' => $presentCount,
                'absent' => $absentCount,
                'late' => $lateCount,
                'excused' => $excusedCount,
                'attendanceRate' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0,
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Attendance stats error: ' . $e->getMessage());
            return response()->json([
                'total_records' => 0,
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'excused' => 0,
                'attendanceRate' => 0,
            ]);
        }
    }
}
