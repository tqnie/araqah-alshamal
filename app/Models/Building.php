<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Building extends Model
{
    use AsSource, Filterable, Attachable;
    protected $fillable = [
        'building_plan_id',
        'building_number',
        'sale',
        'price',
        'area',
        'street_view',
        'direction',
        'type',
        'x',
        'y',
        'width',
        'height',
        'active'
    ];
    public function scopeActive($query)
    {
        return $query->where('active', '1');
    } 
    public function buildingPlan()
    {
        return $this->belongsTo(BuildingPlan::class);
    }
    
}
