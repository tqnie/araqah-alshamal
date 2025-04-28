<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Step extends Model
{
    use HasFactory;use AsSource, Filterable, Attachable;
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body','status','image'
    ];  

    public function scopeActive($query){
        return $query->where('status','ACTIVE');
    }

}
