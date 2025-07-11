<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
        'role' => 'required|in:teacher,student', // Only teachers and students can register
        'phone' => 'nullable|string',
        
        // Teacher-specific fields
        'employee_id' => 'nullable|string',
        'department' => 'nullable|string',
        'specialization' => 'nullable|string',
        'hire_date' => 'nullable|date',
        'address' => 'nullable|string',
        'subject_ids' => 'nullable|array',
        'subject_ids.*' => 'integer|exists:subjects,id',
        'class_ids' => 'nullable|array',
        'class_ids.*' => 'integer|exists:school_classes,id',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'phone' => $validated['phone'] ?? null,
    ]);

    // If registering as teacher, create teacher profile and relationships
    if ($validated['role'] === 'teacher') {
        $teacher = \App\Models\Teacher::create([
            'user_id' => $user->id,
            'employee_id' => $validated['employee_id'] ?? 'TCH' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
            'department' => $validated['department'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'hire_date' => $validated['hire_date'] ?? now(),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => 'active',
        ]);

        // Attach subjects
        if (!empty($validated['subject_ids'])) {
            $teacher->subjects()->attach($validated['subject_ids']);
        }

        // Attach classes
        if (!empty($validated['class_ids'])) {
            $teacher->classes()->attach($validated['class_ids']);
        }
    }

    return response()->json(['message' => 'User registered successfully']);
}

public function login(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'role' => 'sometimes|in:admin,teacher,student' // Optional role for role-specific login
    ]);

    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $user = Auth::user();
    
    // If role is specified, verify it matches the user's actual role
    if (isset($validated['role']) && $user->role !== $validated['role']) {
        Auth::logout();
        return response()->json(['message' => 'Invalid role selected for this account.'], 403);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user,
        'role' => $user->role
    ]);
}

public function me(Request $request)
{
    return response()->json($request->user());
}

public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logged out']);
}

}
