<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockNeed extends Model
{
    use HasFactory;

    public function productionItem()
    {
        return $this->belongsTo(ProductionItem::class);
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }
}
