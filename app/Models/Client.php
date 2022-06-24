<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    public function projectsAsClient()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function projectsAsEndClient()
    {
        return $this->hasMany(Project::class, 'end_client_id');
    }
}
