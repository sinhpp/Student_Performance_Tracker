<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::orderBy('name')->get();
        return response()->json($classes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:10|unique:school_classes',
            'grade_level' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'status' => 'sometimes|in:active,inactive'
        ]);

        $class = SchoolClass::create($validated);
        return response()->json($class, 201);
    }

    public function show(SchoolClass $schoolClass)
    {
        return response()->json($schoolClass->load(['teachers', 'students']));
    }

    public function update(Request $request, SchoolClass $schoolClass)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:10|unique:school_classes,name,' . $schoolClass->id,
            'grade_level' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'status' => 'sometimes|in:active,inactive'
        ]);

        $schoolClass->update($validated);
        return response()->json($schoolClass);
    }

    public function destroy(SchoolClass $schoolClass)
    {
        $schoolClass->delete();
        return response()->json(['message' => 'Class deleted successfully']);
    }
}
