<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    use HasFactory;

    public function type()
    {
        return $this->belongsTo(CatalogType::class, 'catalog_type_id');
    }

    public function boxes()
    {
        return $this->belongsToMany(CatalogBox::class, 'calculation__catalog_box')->withPivot(['pre_rub', 'pre_usd', 'price', 'marzha', 'sborka']);
    }

    public function catalogItems()
    {
        return $this->belongsToMany(CatalogItem::class, 'calculation__catalog_item')->withPivot(['pre_rub', 'pre_usd', 'price']);
    }

    public function delivery()
    {
        return $this->belongsToMany(CalculationDelivery::class, 'calculation__calculation_delivery')->withPivot(['direction_from', 'direction_to', 'days', 'price']);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
