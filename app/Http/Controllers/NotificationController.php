<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index($user_id)
    {
        return Notification::where('user_id', $user_id)->where('is_read', 0)->with('task.column.board')->get();
    }

    public function update($id, Request $request)
    {
        $notification = Notification::find($id);

        if(isset($request->is_read)) {
            $notification->is_read = $request->is_read;
        }

        $notification->save();
    }
}
