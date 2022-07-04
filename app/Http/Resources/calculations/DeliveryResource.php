<?php

namespace App\Http\Resources\calculations;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'from' => $this->pivot->direction_from,
            'to' => $this->pivot->direction_to,
            'days' => $this->pivot->days,
            'price' => $this->price,
        ];
    }
}
