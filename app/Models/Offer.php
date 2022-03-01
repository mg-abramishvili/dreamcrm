<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    public function calculations()
    {
        return $this->belongsToMany(Calculation::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
