<?php

namespace App\Orchid\Resources;

use App\Models\Category;
use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use App\Models\Post;
use App\Models\User;
use Illuminate\Validation\Rule;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Sight;

class PostResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Post::class;


    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('title')
                ->title('ألعنوان')
                ->placeholder('ادخل العنوان هنا'),
            Input::make('slug')
                ->title('slug'),
            Relation::make('category_id')
                ->fromModel(Category::class, 'name')
                ->title('أختار الفسم'),
            Relation::make('user_id')
                ->fromModel(User::class, 'name')
                ->title('المستخدم'),
            TextArea::make('excerpt')
                ->title('نص مختصر')
                ->rows(6),
            Quill::make('body')
                ->title('المحتوي'),
            Picture::make('image')
                ->width(500)
                ->height(300)
                ->horizontal(),

            Input::make('seo_title')
                ->title('عنوان محركات البحث'),
            Input::make('meta_description')
                ->title('ألوصف لمحركات البحث'),
            Input::make('meta_keywords')
                ->title('الكلمات الدليلية'),
            Select::make('status')
                ->title('الحالة')
                ->options(['PUBLISHED'=>'PUBLISHED', 'DRAFT'=>'DRAFT', 'PENDING'=> 'PENDING'])
                ->value('PUBLISHED'),
            Switcher::make('featured')
                ->sendTrueOrFalse()
                ->title('مميز'),

        ];
    }

    /**
     * Get the columns displayed by the resource.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id'),
            TD::make('title'),
            TD::make('category_id', 'الفسم')
                ->render(function ($model) {
                    return $model->category ? $model->category->name : '';
                }),
            
            TD::make('status', 'الحالة')
                ->render(function ($model) {
                    return $model->status == 'PUBLISHED' ? 'منشور' : ($model->status == 'DRAFT' ? 'مسودة' : 'قيد المراجعة');
                }),
            TD::make('featured', 'مميز')
                ->render(function ($model) {
                    return $model->featured ? 'نعم' : 'لا';
                }),
            TD::make('created_at', 'Date of creation')
                ->render(function ($model) {
                    return $model->created_at->toDateTimeString();
                }),

            TD::make('updated_at', 'Update date')
                ->render(function ($model) {
                    return $model->updated_at->toDateTimeString();
                }),
        ];
    }

    /**
     * Get the sights displayed by the resource.
     *
     * @return Sight[]
     */
    public function legend(): array
    {
        return [
            Sight::make('id'),
            Sight::make('title'),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }


    public function rules($model): array
    {
        return [
            'slug' => [
                'required',
                Rule::unique(self::$model, 'slug')->ignore($model),
            ],
        ];
    }
}
