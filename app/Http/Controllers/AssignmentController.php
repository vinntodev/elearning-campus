<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date|after:now'
        ]);

        $course = Course::findOrFail($validated['course_id']);

        if ($course->lecturer_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $assignment = Assignment::create($validated);

        return response()->json([
            'message' => 'Assignment created successfully',
            'assignment' => $assignment
        ], 201);
    }
    
}
