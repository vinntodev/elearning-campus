<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240'
        ]);

        $course = Course::findOrFail($validated['course_id']);

        if ($course->lecturer_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('materials', $filename, 'public');

        $material = Material::create([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'file_path' => $path
        ]);

        return response()->json([
            'message' => 'Material uploaded successfully',
            'material' => $material
        ], 201);
    }

    public function download($id)
    {
        $material = Material::findOrFail($id);

        if (!Storage::disk('public')->exists($material->file_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return Storage::disk('public')->download($material->file_path);
    }
}
