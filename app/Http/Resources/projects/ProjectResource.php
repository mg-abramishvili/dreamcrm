<?php

namespace App\Http\Resources\projects;

use App\Http\Resources\calculations\CalculationsResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'box' => $this->calculations->first()->boxes->first()->name,
            'client' => $this->client,
            'endclient' => $this->endclient,
            'delivery_address' => $this->delivery_address,
            'end_date' => $this->end_date,
            'comment' => $this->comment,
            'user' => $this->user,
            'created_at' => $this->created_at,
            'calculations' => CalculationsResource::collection($this->calculations),
            'offers' => $this->offers,
        ];
    }
}
