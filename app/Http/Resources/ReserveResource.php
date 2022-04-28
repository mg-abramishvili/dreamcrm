<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReserveResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->stockBalance->stockItem->name,
            'production' => $this->productionItem->production->name,
            'quantity' => $this->quantity,
        ];
    }
}
