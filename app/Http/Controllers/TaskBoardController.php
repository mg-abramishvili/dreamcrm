<?php

namespace App\Http\Controllers;

use App\Models\TaskBoard;
use App\Models\Task;
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

    public function board($id, Request $request)
    {
        $user = User::find($request->user()->id);

        $board = TaskBoard::find($id);

        if($user->permissions && $user->permissions->can_see_all_boards == 1 || $board->admin == $user->id) {
            $board = TaskBoard::with(
                [
                    'columns' => function ($q) use($user) {
                        $q->with(
                            [
                                'tasks' => function ($q) use($user) {
                                    $q->with(
                                        [
                                            'users',
                                            'comments',
                                            'notifications' => function ($q) use($user) { $q->where('user_id', $user->id)->where('is_read', 0); }
                                        ]
                                    )
                                    ->orderBy('status', 'asc')
                                    ->orderBy('order', 'asc');
                                }
                            ]
                        )
                        ->orderBy('order', 'asc');
                    }
                ]
            )
            ->find($id);
        } else {
            $board = TaskBoard::with(
                [
                    'columns' => function ($q) use($user) {
                        $q->with(
                            [
                                'tasks' => function ($q) use($user) {
                                    $q->with(
                                        [
                                            'users',
                                            'comments',
                                            'notifications' => function ($q) use($user) { $q->where('user_id', $user->id)->where('is_read', 0); }
                                        ]
                                    )
                                    ->whereRelation('users', 'user_id', $user->id)
                                    ->orderBy('status', 'asc')
                                    ->orderBy('order', 'asc');
                                }
                            ]
                        )
                        ->whereHas('tasks', function ($q) use($user) {
                            $q->whereRelation('users', 'user_id', $user->id);
                        })
                        ->orderBy('order', 'asc');
                    }
                ]
            )
            ->find($id);
        }
        return $board;
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

    public function update($id, Request $request)
    {
        $user = User::find($request->user()->id);

        $board = TaskBoard::find($id);
        
        $board->name = $request->name;

        $board->save();
    }

    public function delete($id)
    {
        $board = TaskBoard::find($id);
        
        foreach($board->columns as $column) {
            foreach($column->tasks as $tsk) {
                $task = Task::find($tsk->id);
                $task->users()->detach();
                $task->comments()->delete();
                $task->files()->delete();
                $task->delete();
            }
            $column->delete();
        }

        $board->delete();
    }
}
