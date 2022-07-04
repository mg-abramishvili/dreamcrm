<?php

namespace App\Http\Resources\calculations;

use Illuminate\Http\Resources\Json\JsonResource;

class CalculationsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'box' => $this->boxes->first()->name,
            'author' => $this->user->name,
            'price' => $this->price + $this->delivery->first()->price,
            'discount' => $this->discount,
            'price_with_discount' => $this->price + $this->delivery->first()->price - ($this->price / 100 * $this->discount),
            'created_at' => $this->created_at,
            'project_id' => $this->project_id,
        ];
    }
}
