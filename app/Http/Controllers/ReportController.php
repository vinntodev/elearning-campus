<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function courseStatistics()
    {
        $courses = Course::withCount('students')
            ->with('lecturer:id,name')
            ->get();

        return response()->json([
            'courses' => $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'lecturer' => $course->lecturer->name,
                    'total_students' => $course->students_count
                ];
            })
        ]);
    }

    public function assignmentStatistics()
    {
        $assignments = Assignment::with('course:id,name')
            ->withCount([
                'submissions',
                'submissions as graded_count' => function ($query) {
                    $query->whereNotNull('score');
                }
            ])
            ->get();

        return response()->json([
            'assignments' => $assignments->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'course' => $assignment->course->name,
                    'total_submissions' => $assignment->submissions_count,
                    'graded' => $assignment->graded_count,
                    'ungraded' => $assignment->submissions_count - $assignment->graded_count,
                    'deadline' => $assignment->deadline
                ];
            })
        ]);
    }

    public function studentStatistics($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);

        $submissions = Submission::where('student_id', $id)
            ->with('assignment:id,title,course_id')
            ->get();

        $statistics = [
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email
            ],
            'total_submissions' => $submissions->count(),
            'graded_submissions' => $submissions->whereNotNull('score')->count(),
            'ungraded_submissions' => $submissions->whereNull('score')->count(),
            'average_score' => round($submissions->whereNotNull('score')->avg('score'), 2),
            'submissions' => $submissions->map(function ($submission) {
                return [
                    'assignment_title' => $submission->assignment->title,
                    'score' => $submission->score,
                    'submitted_at' => $submission->created_at
                ];
            })
        ];

        return response()->json($statistics);
    }
}
