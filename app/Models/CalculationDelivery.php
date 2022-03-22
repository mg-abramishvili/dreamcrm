<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculationDelivery extends Model
{
    use HasFactory;

    protected $casts = [
        'price' => 'float',
    ];

    public function calculations()
    {
        return $this->belongsToMany(Calculation::class)->withPivot(['direction_from', 'direction_to', 'days', 'price']);
    }
}
