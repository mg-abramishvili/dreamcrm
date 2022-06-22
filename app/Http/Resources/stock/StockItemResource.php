<?php

namespace App\Http\Resources\stock;

use App\Models\Production;
use Illuminate\Http\Resources\Json\JsonResource;

class StockItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'balances' => $this->balances,
            'balances_sum_quantity' => $this->balances->sum('quantity'),
            'productions' => Production::whereRelation('items', 'stock_item_id', $this->id)->get(),
        ];
    }
}
