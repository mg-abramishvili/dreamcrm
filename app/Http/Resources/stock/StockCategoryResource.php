<?php

namespace App\Http\Resources\stock;

use App\Http\Resources\stock\StockCategoryItemsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StockCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'items' => StockCategoryItemsResource::collection($this->items),
        ];
    }
}
