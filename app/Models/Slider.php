<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Slider extends Model
{
    use AsSource, Filterable, Attachable,HasFactory; 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'url',
        'status',
        'image',
        'product_id', 
    ];

    public function scopeActive($query)
    {
        return $query->where('active', '1');
    } 
    public function pathImage()
    {
        return $this->hasOne( Attachment::class,'id','image') ;
    }
    public function product()
    {
        return $this->belongsTo(Project::class);
    }
    
}
