<?php

namespace App\Http\Controllers;

use App\Models\TaskBoard;
use App\Models\TaskBoardColumn;
use Illuminate\Http\Request;

class TaskBoardColumnController extends Controller
{
    public function index($id)
    {
        $board = TaskBoard::find($id);

        return TaskBoardColumn::where('board_id', $board->id)->with('tasks.users', 'tasks.comments')->get();
    }

    public function store(Request $request)
    {
        $column = new TaskBoardColumn();

        $column->name = $request->name;
        $column->board_id = $request->board_id;

        $column->save();
    }
}
