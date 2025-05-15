<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'excerpt' => $this->excerpt, 
            'body' => $this->body,
            'image' => $this->image, 
            'location' => $this->location, 
            'building_image' => $this->pathImage?$this->pathImage->url() : null, 
            'count_buildings' => $this->count_buildings, 
            'count_buildings_sold' => $this->count_buildings_sold, 
            'count_buildings_nosold' => $this->count_buildings_nosold, 
            'project_id' => $this->project_id, 
            'type' => $this->type, 
            'active' => $this->active,
            'ago' => $this->created_at->diffForHumans(),
        ];
    }
}
