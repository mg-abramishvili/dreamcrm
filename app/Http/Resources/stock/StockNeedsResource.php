<?php

namespace App\Http\Resources\stock;

use Illuminate\Http\Resources\Json\JsonResource;

class StockNeedsresource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->stockItem->name,
            'production' => $this->productionItem->production->name,
            'quantity' => $this->quantity,
        ];
    }
}
