<?php

namespace App\Http\Resources\stock;

use Illuminate\Http\Resources\Json\JsonResource;

class StockNeedsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'stock_item_id' => $this->stockItem->id,
            'name' => $this->stockItem->name,
            'production' => $this->productionItem->production->name,
            'quantity' => $this->quantity,
        ];
    }
}
