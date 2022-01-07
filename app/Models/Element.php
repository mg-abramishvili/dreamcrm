<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    use HasFactory;

    public function boxes()
    {
        return $this->belongsToMany(Box::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function calculations()
    {
        return $this->belongsToMany(Calculation::class);
    }
}
