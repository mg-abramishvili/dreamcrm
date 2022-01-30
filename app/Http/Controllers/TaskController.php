<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskBoard;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function task($id)
    {
        return Task::with('users', 'comments')->find($id);
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
        if(isset($request->description)) {
            $task->description = $request->description;
        }
        if(isset($request->user_id)) {
            $task->users()->attach($request->user_id);
            // $board = TaskBoard::find($task->column->board->id);
            // $board->users()->sync($request->user_id);
        }
        
        $task->save();
    }
}
