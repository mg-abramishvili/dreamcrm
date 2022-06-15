<?php

namespace App\Http\Resources\stock;

use Illuminate\Http\Resources\Json\JsonResource;

class StockCategoryItemBalancesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}
