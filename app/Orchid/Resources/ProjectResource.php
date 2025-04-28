<?php

namespace App\Orchid\Resources;

use Orchid\Crud\Resource;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class ProjectResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Project::class;
 

    /**
     * The displayable label of the resource.
     *
     * @var string
     */

    public static $label = 'المشاريع';
    public static $title = 'المشاريع';
    public static $group = 'المشاريع';
    public static $description = 'المشاريع';
    public static $icon = 'folder';
    public static $permission = 'platform.systems.projects';
    public static $order = 5;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [

            Input::make('name')->title('اسم المشروع')->required(),
            Input::make('slug')->title('الرابط')->required(),
            TextArea::make('excerpt')->title('وصف المختصر')->required(),
            Quill::make('body')
                ->title('المحتوي'),
            Input::make('location')->title('الموقع')->required(),
            Select::make('type')
                ->title('النوع')
                ->options([
                    'residential' => 'سكني',
                    'commercial' => 'تجاري',
                    'industrial' => 'صناعي',
                ])
                ->empty('Select type')
                ->required(),
            Picture::make('image')->title('الصورة')
                ->width(500)
                ->height(300)
                ->horizontal(),
            Switcher::make('active')->title('حالة المشروع')
                ->sendTrueOrFalse()
                ->value('1')
                ->placeholder('حالة المشروع'),
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
            TD::make('name', 'اسم المشروع')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($model) {
                    return $model->name;
                }),
            TD::make('slug', 'الرابط')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($model) {
                    return $model->slug;
                }),
            TD::make('excerpt', 'وصف مختصر')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($model) {
                    return $model->excerpt;
                }),

            TD::make('image', 'الصورة')
                ->render(function ($model) {
                    return '<img src="' . $model->image . '" alt="' . $model->name . '" style=" height: 100px;" />';
                }),
            TD::make('location', 'الموقع')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($model) {
                    return $model->location;
                }),
            TD::make('type', 'النوع')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($model) {
                    return $model->type;
                }),
            TD::make('active', 'حالة المشروع')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($model) {
                    return $model->active ? 'Yes' : 'No';
                }),

            TD::make('updated_at', '')
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
            Sight::make('name', 'اسم المشروع'),
            Sight::make('excerpt', 'وصف مختصر'),
            Sight::make('body', 'المحتوي'),
            Sight::make('location', 'الموقع'),
            Sight::make('image', 'صورة ')->render(function ($model) {
                return '<img src="' . $model->image . '" alt="' . $model->name . '" style=" max-width: 300px;" />';
            }),
            Sight::make('type', 'النوع'),
            Sight::make('active', 'حالة المشروع'),
            Sight::make('created_at', 'تاريخ الانشاء')
                ->render(function ($model) {
                    return $model->created_at->toDateTimeString();
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
