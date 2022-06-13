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
            'created_at' => $this->created_at,
        ];
    }
}
