<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskBoardColumn extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function board()
    {
        return $this->belongsTo(TaskBoard::class, 'board_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'column_id');
    }
}
