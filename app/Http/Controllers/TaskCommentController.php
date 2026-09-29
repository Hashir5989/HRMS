<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $this->authorize('task.view');
        
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $task->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function update(Request $request, TaskComment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $comment->update(['comment' => $request->comment]);

        return back()->with('success', 'Comment updated.');
    }

    public function destroy(TaskComment $comment)
    {
        if ($comment->user_id !== auth()->id() && !auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
