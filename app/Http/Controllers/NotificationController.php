<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index($user_id)
    {
        return Notification::where('user_id', $user_id)->with('task')->get();
    }
}
