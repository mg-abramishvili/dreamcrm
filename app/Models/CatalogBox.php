<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogBox extends Model
{
    use HasFactory;

    protected $casts = [
        'sborka_days' => 'integer',
        'sborka_persons' => 'integer',
        'sborka' => 'float',
        'marzha' => 'float',
        'pre_rub' => 'float',
        'pre_usd' => 'float',
        'price' => 'float',
        'length' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'weight' => 'integer',
        'gallery' => 'json'
    ];

    public function types()
    {
        return $this->belongsToMany(CatalogType::class, 'catalog_box__catalog_type', 'catalog_box_id', 'catalog_type_id');
    }

    public function items()
    {
        return $this->belongsToMany(CatalogItem::class, 'catalog_box__catalog_item', 'catalog_box_id', 'catalog_item_id');
    }

    public function stockItems()
    {
        return $this->belongsToMany(StockItem::class, 'catalog_box__stock_item', 'catalog_box_id', 'stock_item_id')->withPivot(['quantity']);
    }

    public function calculations()
    {
        return $this->belongsToMany(Calculation::class, 'calculation__catalog_box');
    }
}
