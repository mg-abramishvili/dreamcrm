<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'task__user');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function column()
    {
        return $this->belongsTo(TaskBoardColumn::class, 'column_id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
