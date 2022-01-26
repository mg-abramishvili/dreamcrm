<?php

namespace App\Http\Controllers;

use App\Models\TaskComment;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function index($id)
    {
        return TaskComment::where('task_id', $id)->with('user')->get();
    }

    public function store($id, Request $request)
    {
        $taskComment = new TaskComment();

        $taskComment->task_id = $id;
        $taskComment->user_id = $request->user_id;
        $taskComment->text = $request->text;

        $taskComment->save();
    }
}
