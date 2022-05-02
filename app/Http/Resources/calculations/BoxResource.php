<?php

namespace App\Http\Resources\calculations;

use App\Http\Resources\calculations\StockItemsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BoxResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'pre_rub' => $this->pivot->pre_rub,
            'pre_usd' => $this->pivot->pre_usd,
            'sborka' => $this->pivot->sborka,
            'marzha' => $this->pivot->marzha,
            'price' => $this->pivot->price,
            'stock_items' => StockItemsResource::collection($this->stockItems),
        ];
    }
}
