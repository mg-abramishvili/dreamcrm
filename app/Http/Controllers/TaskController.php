<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find($request->user()->id);

        if($user->task_permissions && $user->task_permissions->can_see_all_tasks == 1) {
            return Task::with('users', 'comments')->get();
        }

        return Task::with('users', 'comments')->whereRelation('users', 'user_id', $user->id)->get();
    }
}
