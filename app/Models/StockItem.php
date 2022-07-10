<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(StockCategory::class);
    }

    public function balances()
    {
        return $this->hasMany(StockBalance::class, 'stock_item_id');
    }

    public function latestBalance()
    {
        return $this->hasOne(StockBalance::class, 'stock_item_id')->orderBy('date', 'desc');
    }

    public function catalogItems()
    {
        return $this->belongsToMany(CatalogItem::class, 'catalog_item__stock_item', 'stock_item_id', 'catalog_item_id')->withPivot(['quantity']);
    }

    public function catalogBoxes()
    {
        return $this->belongsToMany(CatalogBox::class, 'catalog_box__stock_item', 'stock_item_id', 'catalog_box_id')->withPivot(['quantity']);
    }

    public function productionItems()
    {
        return $this->hasMany(ProductionItem::class);
    }

    public function needs()
    {
        return $this->hasMany(StockNeed::class);
    }
}
