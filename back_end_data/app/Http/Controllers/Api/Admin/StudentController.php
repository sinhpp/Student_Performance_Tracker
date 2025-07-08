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
    public function index()
    {
        $students = Student::with('user')->get();
        return response()->json($students);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'class' => 'required|string',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'status' => 'required|in:active,inactive,graduated',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
        ]);

        $student = Student::create([
            'user_id' => $user->id,
            'class' => $validated['class'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'status' => $validated['status'],
        ]);

        $student->load('user');
        return response()->json($student, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load('user', 'grades.subject', 'attendance', 'feedbacks.creator');
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $student->user_id,
            'class' => 'sometimes|string',
            'dob' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female,other',
            'status' => 'sometimes|in:active,inactive,graduated',
        ]);

        if (isset($validated['name']) || isset($validated['email'])) {
            $student->user->update([
                'name' => $validated['name'] ?? $student->user->name,
                'email' => $validated['email'] ?? $student->user->email,
            ]);
        }

        $student->update(array_intersect_key($validated, array_flip(['class', 'dob', 'gender', 'status'])));

        $student->load('user');
        return response()->json($student);
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
