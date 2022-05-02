<?php

namespace App\Http\Resources\calculations;

use Illuminate\Http\Resources\Json\JsonResource;

class StockItemsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'quantity' => $this->pivot->quantity,
        ];
    }
}
