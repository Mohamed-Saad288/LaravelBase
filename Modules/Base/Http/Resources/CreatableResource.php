<?php

namespace Modules\Base\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CreatableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name ?? $this->title ?? null,
            "email" => $this->email ?? null,
            "type" => class_basename($this->resource),
        ];
    }
}
