<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class BuildingPlan extends Model
{
    use AsSource, Filterable, Attachable;
    protected $fillable = [
        'title',
        'excerpt',
        'body',
        'slug',
        'image',
        'location',
        'plan_image',
        'project_id',
        'type',
        'active'
    ];
    public function scopeActive($query)
    {
        return $query->where('active', '1');
    } 
    public function buildings()
    {
        return $this->hasMany(Building::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function pathImage()
    {
        return $this->hasOne( Attachment::class,'id','plan_image') ;
    }
    public function pathImages()
    {
        return $this->hasMany( Attachment::class,'id','plan_image') ;
    }
}
