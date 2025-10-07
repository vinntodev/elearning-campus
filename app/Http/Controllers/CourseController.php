<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::with('lecturer')->get();
        return response()->json([
            'courses' => $courses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $course = Course::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'lecturer_id' => $request->user()->id
        ]);

        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course->load('lecturer')
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        if ($course->lecturer_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $course->update($validated);

        return response()->json([
            'message' => 'Course updated successfully',
            'course' => $course
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        if ($course->lecturer_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $course->delete();

        return response()->json([
            'message' => 'Course deleted successfully'
        ]);
    }

    public function enroll(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $user = $request->user();

        if ($user->coursesAsStudent()->where('course_id', $id)->exists()) {
            return response()->json([
                'message' => 'Already enrolled in this course'
            ], 400);
        }

        $user->coursesAsStudent()->attach($id);

        return response()->json([
            'message' => 'Successfully enrolled in course',
            'course' => $course
        ]);
    }
}
