<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function calculations()
    {
        return $this->hasMany(Calculation::class, 'project_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'project_id');
    }
}
