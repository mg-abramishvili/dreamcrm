<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    use HasFactory;

    protected $casts = ['gallery' => 'json'];

    public function types()
    {
        return $this->belongsToMany(CatalogType::class);
    }

    public function items()
    {
        return $this->belongsToMany(CatalogItem::class);
    }

    public function calculations()
    {
        return $this->belongsToMany(Calculation::class);
    }
}
