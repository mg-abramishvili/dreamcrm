<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    use HasFactory;

    public function boxes()
    {
        return $this->belongsToMany(Box::class);
    }

    public function elements()
    {
        return $this->belongsToMany(Element::class);
    }
}
