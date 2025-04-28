<?php

namespace App\Orchid\Resources;

use Orchid\Crud\Resource;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class PageResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Page::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {

        return [
            Input::make('title')
                ->title('العنوان')
                ->placeholder('ادخل العنوان')
                ->required(),
            Input::make('slug')
                ->title('الرابط')
                ->placeholder('ادخل الرابط')
                ->required(),
            Quill::make('body')
                ->title('المحتوي')
                ->placeholder('ادخل المحتوي'),
            Input::make('meta_description')
                ->title('وصف محركات البحث')
                ->placeholder('ادخل وصف محركات البحث'),
            Input::make('meta_keywords')
                ->title('كلمات مفتاحية')
                ->placeholder('ادخل الكلمات المفتاحية'),
            Picture::make('image')
                ->title('صورة')
                ->placeholder('اختر صورة'),

            Select::make('status')
                ->title('الحالى')
                ->options(['ACTIVE', 'INACTIVE'])
                ->value('ACTIVE'),
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
            TD::make('title', 'العنوان')
                ->sort()
                ->filter(TD::FILTER_TEXT),

            TD::make('image', 'الصورة')
                ->render(function ($model) {
                    return '<img src="' . $model->image . '" alt="' . $model->title . '" style=" height: 100px;" />';
                }),
            TD::make('status', 'الحالى')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($model) {
                    return $model->status == 'ACTIVE' ? 'مفعل' : 'غير مفعل';
                }),
            TD::make('created_at', 'تاريخ الانشاء')
                ->render(function ($model) {
                    return $model->created_at->toDateTimeString();
                }),
            TD::make('updated_at', 'تاريخ التحديث')
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
            Sight::make('title', 'العنوان'),
            Sight::make('slug', 'الرابط'),
            Sight::make('body', 'المحتوي') 
                ->render(function ($model) {
                    return $model->body;
                }),
            Sight::make('meta_description', 'وصف محركات البحث'),
            Sight::make('meta_keywords', 'الكلمات المفتاحية'),
            Sight::make('image', 'الصورة')
                ->render(function ($model) {
                    return '<img src="' . $model->image . '" alt="' . $model->title . '" style=" height: 100px;" />';
                }),
            Sight::make('status', 'الحالى')

                ->render(function ($model) {
                    return $model->status == 'ACTIVE' ? 'مفعل' : 'غير مفعل';
                }),
            Sight::make('created_at', 'تاريخ الانشاء')
                ->render(function ($model) {
                    return $model->created_at->toDateTimeString();
                }),
            Sight::make('updated_at', 'تاريخ التحديث')
                ->render(function ($model) {
                    return $model->updated_at->toDateTimeString();
                }),
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
}
