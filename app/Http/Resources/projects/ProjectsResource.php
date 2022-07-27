<?php

namespace App\Http\Resources\projects;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'box' => $this->calculations->first()->boxes->first()->name,
            'endclient' => isset($this->endclient->name) ? $this->endclient->name : null,
            'endclient_inn' => isset($this->endclient->inn) ? $this->endclient->inn : null,
            'author' => $this->user->name,
            'created_at' => $this->created_at,
        ];
    }
}
