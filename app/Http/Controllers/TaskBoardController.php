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

        return TaskBoard::with('users')->whereRelation('users', 'user_id', $user->id)->get();
    }
}
