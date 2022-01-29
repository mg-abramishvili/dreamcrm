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

        if($user->task_board_permissions && $user->task_board_permissions->can_see_all_boards == 1) {
            return TaskBoard::all();
        }

        return TaskBoard::with('users')->whereRelation('users', 'user_id', $user->id)->orWhere('admin', $user->id)->get();
    }
}
