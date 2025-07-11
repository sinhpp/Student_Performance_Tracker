<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with('user');

        // Filter by student name
        if ($request->has('name') && $request->name) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        // Filter by student ID
        if ($request->has('student_id') && $request->student_id) {
            $studentIdNumber = str_replace('STU', '', $request->student_id);
            $studentIdNumber = ltrim($studentIdNumber, '0');
            if (is_numeric($studentIdNumber)) {
                $query->where('id', $studentIdNumber);
            }
        }

        // Filter by class
        if ($request->has('class') && $request->class) {
            $query->where('class', $request->class);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Sort by class
        if ($request->has('sort_by_class')) {
            $direction = $request->sort_by_class === 'desc' ? 'desc' : 'asc';
            $query->orderBy('class', $direction);
        }

        // Sort by status
        if ($request->has('sort_by_status')) {
            $direction = $request->sort_by_status === 'desc' ? 'desc' : 'asc';
            $query->orderBy('status', $direction);
        }

        // Default sorting by created_at if no specific sort is applied
        if (!$request->has('sort_by_class') && !$request->has('sort_by_status')) {
            $query->orderBy('created_at', 'desc');
        }

        $students = $query->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'student_id' => 'STU' . str_pad($student->id, 3, '0', STR_PAD_LEFT),
                'phone' => $student->user->phone ?? '+1234567890',
                'class_name' => $student->class,
                'status' => $student->status,
                'dob' => $student->dob,
                'gender' => $student->gender,
                'created_at' => $student->created_at,
                'updated_at' => $student->updated_at,
            ];
        });
        
        return response()->json([
            'data' => $students,
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $students->count(),
                'total' => $students->count(),
                'filters' => [
                    'name' => $request->name,
                    'student_id' => $request->student_id,
                    'class' => $request->class,
                    'status' => $request->status,
                ],
                'sort' => [
                    'sort_by_class' => $request->sort_by_class,
                    'sort_by_status' => $request->sort_by_status,
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'password' => 'required|min:6',
            'class' => 'required|string',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'status' => 'required|in:active,inactive,graduated',
            'address' => 'nullable|string',
            'guardian_name' => 'nullable|string',
            'guardian_phone' => 'nullable|string',
            'guardian_email' => 'nullable|email',
            'guardian_relation' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
        ]);

        $student = Student::create([
            'user_id' => $user->id,
            'class' => $validated['class'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'status' => $validated['status'],
            'address' => $validated['address'],
            'guardian_name' => $validated['guardian_name'],
            'guardian_phone' => $validated['guardian_phone'],
            'guardian_email' => $validated['guardian_email'],
            'guardian_relation' => $validated['guardian_relation'],
        ]);

        $student->load('user');
        
        $formattedStudent = [
            'id' => $student->id,
            'name' => $student->user->name,
            'email' => $student->user->email,
            'student_id' => 'STU' . str_pad($student->id, 3, '0', STR_PAD_LEFT),
            'phone' => $student->user->phone ?? '+1234567890',
            'class_name' => $student->class,
            'status' => $student->status,
            'dob' => $student->dob,
            'gender' => $student->gender,
            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at,
        ];
        
        return response()->json($formattedStudent, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load('user', 'grades.subject', 'attendance');
        
        $formattedStudent = [
            'id' => $student->id,
            'name' => $student->user->name,
            'email' => $student->user->email,
            'phone' => $student->user->phone,
            'student_id' => 'STU' . str_pad($student->id, 3, '0', STR_PAD_LEFT),
            'class_name' => $student->class,
            'class' => $student->class, // For form compatibility
            'status' => $student->status,
            'dob' => $student->dob,
            'date_of_birth' => $student->dob ? $student->dob->format('Y-m-d') : null, // For form compatibility
            'gender' => $student->gender,
            'address' => $student->address,
            'guardian_name' => $student->guardian_name,
            'guardian_phone' => $student->guardian_phone,
            'guardian_email' => $student->guardian_email,
            'guardian_relation' => $student->guardian_relation,
            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at,
        ];
        
        return response()->json($formattedStudent);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $student->user_id,
            'phone' => 'sometimes|nullable|string',
            'class' => 'sometimes|string',
            'date_of_birth' => 'sometimes|nullable|date',
            'dob' => 'sometimes|nullable|date',
            'gender' => 'sometimes|in:male,female,other',
            'status' => 'sometimes|in:active,inactive,graduated',
            'address' => 'sometimes|nullable|string',
            'guardian_name' => 'sometimes|nullable|string',
            'guardian_phone' => 'sometimes|nullable|string',
            'guardian_email' => 'sometimes|nullable|email',
            'guardian_relation' => 'sometimes|nullable|string',
        ]);

        // Update user information
        if (isset($validated['name']) || isset($validated['email']) || isset($validated['phone'])) {
            $student->user->update([
                'name' => $validated['name'] ?? $student->user->name,
                'email' => $validated['email'] ?? $student->user->email,
                'phone' => $validated['phone'] ?? $student->user->phone,
            ]);
        }

        // Handle date field (support both 'dob' and 'date_of_birth')
        $dob = $validated['dob'] ?? $validated['date_of_birth'] ?? null;
        
        // Update student information
        $studentData = [
            'class' => $validated['class'] ?? $student->class,
            'dob' => $dob,
            'gender' => $validated['gender'] ?? $student->gender,
            'status' => $validated['status'] ?? $student->status,
            'address' => $validated['address'] ?? $student->address,
            'guardian_name' => $validated['guardian_name'] ?? $student->guardian_name,
            'guardian_phone' => $validated['guardian_phone'] ?? $student->guardian_phone,
            'guardian_email' => $validated['guardian_email'] ?? $student->guardian_email,
            'guardian_relation' => $validated['guardian_relation'] ?? $student->guardian_relation,
        ];

        $student->update($studentData);
        $student->load('user');
        
        // Return formatted response
        $formattedStudent = [
            'id' => $student->id,
            'name' => $student->user->name,
            'email' => $student->user->email,
            'phone' => $student->user->phone,
            'student_id' => 'STU' . str_pad($student->id, 3, '0', STR_PAD_LEFT),
            'class_name' => $student->class,
            'class' => $student->class,
            'status' => $student->status,
            'dob' => $student->dob,
            'date_of_birth' => $student->dob ? $student->dob->format('Y-m-d') : null,
            'gender' => $student->gender,
            'address' => $student->address,
            'guardian_name' => $student->guardian_name,
            'guardian_phone' => $student->guardian_phone,
            'guardian_email' => $student->guardian_email,
            'guardian_relation' => $student->guardian_relation,
            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at,
        ];
        
        return response()->json($formattedStudent);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->user->delete(); // This will cascade delete the student
        return response()->json(['message' => 'Student deleted successfully']);
    }
}
