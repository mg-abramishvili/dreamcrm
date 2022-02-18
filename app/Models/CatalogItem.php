<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogItem extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(CatalogCategory::class);
    }

    public function stockItems()
    {
        return $this->belongsToMany(StockItem::class, 'catalog_item__stock_item', 'catalog_item_id', 'stock_item_id');
    }

    public function boxes()
    {
        return $this->belongsToMany(CatalogBox::class, 'catalog_box__catalog_item', 'catalog_item_id', 'catalog_box_id');
    }

    public function calculations()
    {
        return $this->belongsToMany(Calculation::class);
    }
}