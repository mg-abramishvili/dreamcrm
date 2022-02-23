<?php

namespace App\Mail;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function build()
    {
        return $this->subject('DreamCRM - ' . $this->notification->name)
            ->markdown('emails.notification')
            ->with([
            'name' => $this->notification->name,
            'task' => $this->notification->task->name,
        ]);
    }
}