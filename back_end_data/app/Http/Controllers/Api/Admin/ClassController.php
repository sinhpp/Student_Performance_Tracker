<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = [
            ['id' => 1, 'name' => 'Grade 1'],
            ['id' => 2, 'name' => 'Grade 2'],
            ['id' => 3, 'name' => 'Grade 3'],
            ['id' => 4, 'name' => 'Grade 4'],
            ['id' => 5, 'name' => 'Grade 5'],
            ['id' => 6, 'name' => 'Grade 6'],
            ['id' => 7, 'name' => 'Grade 7'],
            ['id' => 8, 'name' => 'Grade 8'],
            ['id' => 9, 'name' => 'Grade 9'],
            ['id' => 10, 'name' => 'Grade 10'],
        ];

        return response()->json(['data' => $classes]);
    }
}
