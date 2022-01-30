<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskBoard extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function columns()
    {
        return $this->hasMany(TaskBoardColumn::class, 'board_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
