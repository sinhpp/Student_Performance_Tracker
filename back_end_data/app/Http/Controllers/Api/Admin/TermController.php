<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index()
    {
        $terms = Term::all();
        return response()->json(['data' => $terms]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        $term = Term::create($request->all());
        return response()->json($term, 201);
    }

    public function show(Term $term)
    {
        return response()->json($term);
    }

    public function update(Request $request, Term $term)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        $term->update($request->all());
        return response()->json($term);
    }

    public function destroy(Term $term)
    {
        $term->delete();
        return response()->json(null, 204);
    }
}
