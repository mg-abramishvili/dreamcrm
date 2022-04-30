<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use App\Traits\createNotification;

class TaskCommentController extends Controller
{
    use createNotification;

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

        $task = Task::find($taskComment->task_id);

        foreach($task->users as $user) {
            if($user->id == $taskComment->user_id) {
                return;
            }

            $this->createNotification($user, 'task', $task, 'Новый комментарий');
        }
    }
}
