<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        return [
            'id' => $this->id,
            'building_plan_id' => $this->building_plan_id,
            'building_number' => $this->building_number,
            'sale' => $this->sale,
            'price' => $this->price,
            'area' => $this->area,
            'street_view' => $this->street_view,
            'direction' => $this->direction,
            'type' => $this->type,
            'x' => $this->x,
            'y' => $this->y,
            'width' => $this->width,
            'height' => $this->height,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
