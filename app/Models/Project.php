<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;


class Project extends Model
{
    use AsSource, Filterable, Attachable;
    protected $fillable = [
        'name',
        'slug',
        'excerpt',
        'body',
        'image',
        'location', 
        'type',
        'active'
    ];
    public function scopeActive($query)
    {
        return $query->where('active', '1');
    } 
    public function buildingPlans()
    {
        return $this->hasMany(BuildingPlan::class);
    }
}
