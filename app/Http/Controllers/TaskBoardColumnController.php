<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskBoard;
use App\Models\TaskBoardColumn;
use App\Models\User;
use Illuminate\Http\Request;

class TaskBoardColumnController extends Controller
{
    public function store(Request $request)
    {
        $column = new TaskBoardColumn();

        $column->name = $request->name;
        $column->board_id = $request->board_id;
        $column->order = 99;

        $column->save();
    }

    public function rename($id, Request $request)
    {
        $column = TaskBoardColumn::find($id);

        $column->name = $request->name;

        $column->save();
    }

    public function reorder(Request $request)
    {
        $columns = $request->columns;

        foreach($columns as $clmn) {
            $column = TaskBoardColumn::find($clmn["id"]);
            $column->order = $clmn["index"];
            $column->save();
        }

        // return 'OK';
    }

    public function delete($id)
    {
        $column = TaskBoardColumn::find($id);

        foreach($column->tasks as $tsk) {
            $task = Task::find($tsk->id);
            $task->users()->detach();
            $task->comments()->delete();
            $task->files()->delete();
            $task->delete();
        }

        $column->delete();
    }
}
