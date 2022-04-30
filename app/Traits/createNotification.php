<?php

namespace App\Traits;

use App\Models\Notification;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;

trait createNotification
{
    public function createNotification($user, $model, $modelObject, $subject)
    {
        $notification = new Notification();

        $notification->user_id = $user->id;
        $notification->is_read = false;
        $notification->name = $subject;

        if($model == 'task') {
            $notification->task_id = $modelObject->id;
        }

        $notification->save();

        Mail::to($user->email)->send(new NotificationMail($notification));
    }
}