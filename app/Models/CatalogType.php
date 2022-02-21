<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogType extends Model
{
    use HasFactory;

    public function boxes()
    {
        return $this->belongsToMany(CatalogBox::class, 'catalog_box__catalog_type', 'catalog_type_id', 'catalog_box_id');
    }
}
