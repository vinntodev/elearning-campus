<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'file_path' => 'required|file|mimes:pdf,doc,docx,zip|max:10240'
        ]);

        $assignment = Assignment::findOrFail($validated['assignment_id']);

        if ($assignment->deadline < now()) {
            return response()->json([
                'message' => 'Assignment deadline has passed'
            ], 400);
        }
        $existingSubmission = Submission::where('assignment_id', $validated['assignment_id'])
            ->where('student_id', $request->user()->id)
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'message' => 'You have already submitted this assignment'
            ], 400);
        }

        $file = $request->file('file_path');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('submissions', $filename, 'public');

        $submission = Submission::create([
            'assignment_id' => $validated['assignment_id'],
            'student_id' => $request->user()->id,
            'file_path' => $path
        ]);

        return response()->json([
            'message' => 'Submission uploaded successfully',
            'submission' => $submission
        ]);
    }

    public function grade(Request $request, $id)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100'
        ]);

        $submission = Submission::with('assignment.course')->findOrFail($id);

        if ($submission->assignment->course->lecturer_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $submission->update(['score' => $validated['score']]);

        return response()->json([
            'message' => 'Submission graded successfully',
            'submission' => $submission
        ]);
    }
}
