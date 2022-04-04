<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    use HasFactory;

    public function productionItem()
    {
        return $this->belongsTo(ProductionItem::class);
    }

    public function stockBalance()
    {
        return $this->belongsTo(StockBalance::class);
    }
}
