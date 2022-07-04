<?php

namespace App\Http\Resources\calculations;

use App\Http\Resources\calculations\BoxResource;
use App\Http\Resources\calculations\CatalogItemsResource;
use App\Http\Resources\calculations\DeliveryResource;

use Illuminate\Http\Resources\Json\JsonResource;

class CalculationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'type' => $this->type->name,
            'box' => new BoxResource($this->boxes->first()),
            'catalog_items' => CatalogItemsResource::collection($this->catalogItems),
            'delivery' => new DeliveryResource($this->delivery->first()),
            'project_id' => isset($this->project->id) ? $this->project->id : null,
            'user' => $this->user->name,
            'quantity' => $this->quantity,
        ];
    }
}
