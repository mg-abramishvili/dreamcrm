<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItem extends Model
{
    use HasFactory;

    public function reserves()
    {
        return $this->hasMany(Reserve::class);
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function stockNeeds()
    {
        return $this->hasMany(StockNeed::class);
    }
}
