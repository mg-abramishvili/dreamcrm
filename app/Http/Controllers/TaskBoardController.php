<?php

namespace App\Http\Controllers;

use App\Models\TaskBoard;
use App\Models\User;
use Illuminate\Http\Request;

class TaskBoardController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find($request->user()->id);

        if($user->permissions && $user->permissions->can_see_all_boards == 1) {
            return TaskBoard::all();
        }

        return TaskBoard::whereRelation('columns.tasks.users', 'user_id', $user->id)->orWhere('admin', $user->id)->get();
    }

    public function store(Request $request)
    {
        $user = User::find($request->user()->id);

        $board = new TaskBoard();

        $board->name = $request->name;
        $board->admin = $user->id;
        $board->is_active = true;

        $board->save();

        return $board;
    }
}
