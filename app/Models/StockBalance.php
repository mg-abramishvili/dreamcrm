<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBalance extends Model
{
    use HasFactory;

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }

    public function reserves()
    {
        return $this->hasMany(Reserve::class);
    }
}
