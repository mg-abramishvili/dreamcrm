<?php

namespace App\Http\Resources\stock;

use App\Http\Resources\stock\StockCategoryItemBalancesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StockCategoryItemsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'balance' => $this->balances->sum('quantity'),
            'balances' => StockCategoryItemBalancesResource::collection($this->balances),
            'needs' => count($this->needs),
        ];
    }
}
