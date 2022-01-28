<?php

namespace App\Http\Controllers;

use App\Models\TaskBoard;
use App\Models\TaskBoardColumn;
use App\Models\User;
use Illuminate\Http\Request;

class TaskBoardColumnController extends Controller
{
    public function index($id, Request $request)
    {
        $board = TaskBoard::find($id);

        $user = User::find($request->user()->id);

        if($user->task_board_permissions && $user->task_board_permissions->can_see_all_boards == 1 || $board->admin == $user->id) {
            return TaskBoardColumn::where('board_id', $board->id)->with('board', 'tasks.users', 'tasks.comments')->get();
        }

        return TaskBoardColumn::where('board_id', $board->id)->with(
            [
                'board',
                'tasks' => function ($q) use($user) {
                    $q->with('users', 'comments')->whereRelation('users', 'user_id', $user->id);
                },
            ],
        )
        ->whereHas('tasks')
        ->get();
    }

    public function store(Request $request)
    {
        $column = new TaskBoardColumn();

        $column->name = $request->name;
        $column->board_id = $request->board_id;

        $column->save();
    }
}