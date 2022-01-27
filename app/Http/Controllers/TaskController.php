<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskBoard;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index($id, Request $request)
    {
        $user = User::find($request->user()->id);

        $board = TaskBoard::find($id);

        if($user->task_permissions && $user->task_permissions->can_see_all_tasks == 1) {
            return Task::with('users', 'comments')->where('board_id', $board->id)->get();
        }

        return Task::with('users', 'comments')->where('board_id', $board->id)->whereRelation('users', 'user_id', $user->id)->get();
    }
}
