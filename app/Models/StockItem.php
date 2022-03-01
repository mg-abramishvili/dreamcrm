<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;

    public function balances()
    {
        return $this->hasMany(StockBalance::class, 'stock_item_id');
    }

    public function catalogItems()
    {
        return $this->belongsToMany(CatalogItem::class, 'catalog_item__stock_item', 'stock_item_id', 'catalog_item_id')->withPivot(['quantity']);
    }

    public function catalogBoxes()
    {
        return $this->belongsToMany(CatalogBox::class);
    }
}
