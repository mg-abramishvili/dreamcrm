<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskBoard;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function task($id, Request $request)
    {
        $user = User::find($request->user()->id);

        return Task::with(
            [
                'users',
                'comments',
                'files.user',
                'column.board',
                'notifications' => function ($q) use($user) {
                    $q->where('user_id', $user->id);
                }
            ]
        )
        ->find($id);
    }

    public function store(Request $request)
    {
        $task = new Task();

        $user = User::find($request->user()->id);

        $task->column_id = $request->column_id;
        $task->name = $request->name;

        if(isset($request->description)) {
            $task->description = $request->description;
        }

        $task->status = 'active';
        $task->order = 99;

        $task->save();

        $task->users()->sync($user->id);
    }

    public function update($id, Request $request)
    {
        $task = Task::find($id);

        if(isset($request->column_id)) {
            $task->column_id = $request->column_id;
        }

        if(isset($request->name)) {
            $task->name = $request->name;
        }

        if(isset($request->description)) {
            $task->description = $request->description;
        }

        if(isset($request->user_id)) {
            if($task->users()->wherePivot('user_id', $request->user_id)->exists()) {
                $task->users()->detach($request->user_id);
            } else {
                $task->users()->attach($request->user_id);
            }
        }

        if(isset($request->deadline)) {
            $task->deadline = $request->deadline;
        }
        
        if(isset($request->status)) {
            $task->status = $request->status;

            if($request->status == 'completed') {
                $notification = new Notification();
                $notification->task_id = $task->id;
                $notification->user_id = $task->column->board->admin;
                $notification->is_read = false;
                $notification->name = '???????????? ??????????????????';
                $notification->save();
                Mail::to(User::find($task->column->board->admin)->email)->send(new NotificationMail($notification));
            }
        }

        $task->save();
    }

    public function reorder(Request $request)
    {
        $tasks = $request->tasks;

        foreach($tasks as $tsk) {
            $task = Task::find($tsk["id"]);
            $task->order = $tsk["index"];
            $task->save();
        }

        // return 'OK';
    }

    public function delete($id)
    {
        $task = Task::find($id);
        $task->users()->detach();
        $task->comments()->delete();
        $task->files()->delete();
        $task->delete();
    }
}
