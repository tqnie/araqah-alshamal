<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Category extends Model
{
    use AsSource, Filterable, Attachable;
    protected $fillable = [
        'name','slug','description','is_visible','seo_title','seo_description'
     ];
     
}
