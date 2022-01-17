<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    use HasFactory;

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function boxes()
    {
        return $this->belongsToMany(Box::class);
    }

    public function elements()
    {
        return $this->belongsToMany(Element::class)->withPivot(['pre_rub', 'pre_usd', 'price']);
    }

    public function delivery()
    {
        return $this->belongsToMany(Delivery::class)->withPivot(['direction_from', 'direction_to', 'days', 'price']);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
