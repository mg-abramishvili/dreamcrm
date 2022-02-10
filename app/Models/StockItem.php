<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;

    // public function category()
    // {
    //     return $this->belongsTo(StockCategory::class, 'stock_items', 'id', 'category_id');
    // }
}
