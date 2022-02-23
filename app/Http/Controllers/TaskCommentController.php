<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;

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

        $task = Task::find($taskComment->task_id);

        foreach($task->users as $user) {
            $notification = new Notification();
            $notification->task_id = $task->id;
            $notification->user_id = $user->id;
            $notification->is_read = false;
            $notification->name = 'Новый комментарий';
            $notification->save();
            Mail::to($user->email)->send(new NotificationMail($notification));
        }
    }
}
