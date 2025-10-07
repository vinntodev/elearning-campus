<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Discussion;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'content' => 'required|string'
        ]);

        $discussion = Discussion::create([
            'course_id' => $validated['course_id'],
            'user_id' => $request->user()->id,
            'content' => $validated['content']
        ]);

        return response()->json([
            'message' => 'Discussion created successfully',
            'discussion' => $discussion->load('user')
        ]);
    }

    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        $discussion = Discussion::findOrFail($id);

        $reply = Reply::create([
            'discussion_id' => $id,
            'user_id' => $request->user()->id,
            'content' => $validated['content']
        ]);

        return response()->json([
            'message' => 'Reply added successfully',
            'reply' => $reply->load('user')
        ]);
    }
}
