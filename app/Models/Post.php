<?php

namespace App\Models;

use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Str;

class Post extends Model
{
    use AsSource, Filterable, Attachable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'title',
        'seo_title',
        'excerpt',
        'body',
        'image',
        'slug',
        'meta_description',
        'meta_keywords',
        'status',
        'featured',
        'user_id'
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id'         => Where::class,
        'title'       => Like::class,
        'slug'      => Like::class,
        'updated_at' => WhereDateStartEnd::class,
        'created_at' => WhereDateStartEnd::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'title',
        'slug',
        'updated_at',
        'created_at',
    ];
    public function scopeSearch($query, $search)
    {
        if ($search != '')
            return   $query->where('title', 'Like', '%' . $search . '%')->orWhere('body', 'Like', '%' . $search . '%');
    }
    public function scopeActive()
    {
        return $this->where('status', 'PUBLISHED');
    }
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
