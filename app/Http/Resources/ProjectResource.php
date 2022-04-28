<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'box' => $this->calculations->first()->boxes->first()->name,
            'client' => $this->client->name,
            'author' => $this->user->name,
            'created_at' => $this->created_at,
        ];
    }
}
