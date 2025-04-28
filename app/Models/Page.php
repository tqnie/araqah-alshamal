<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Page extends Model
{
    use AsSource, Filterable, Attachable;
    protected $fillable = [
        'title','excerpt','body','body_builder',
        'image', 'slug', 'meta_description','meta_keywords','status'
    ];
    public function scopeIsActive()
    {
        return $this->where('status','PUBLISHED');
    }
}