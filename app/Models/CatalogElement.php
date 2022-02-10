<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogElement extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockItems()
    {
        return $this->hasMany(StockItem::class);
    }

    public function boxes()
    {
        return $this->belongsToMany(Box::class);
    }

    public function calculations()
    {
        return $this->belongsToMany(Calculation::class);
    }
}
