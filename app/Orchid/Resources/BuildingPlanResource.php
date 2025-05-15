<?php

namespace App\Orchid\Resources;

use App\Models\Project;
use Orchid\Crud\Resource;
use Orchid\Screen\Fields\Attach;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class BuildingPlanResource extends Resource
{

    public static $label = 'المخططات';
    public static $title = 'المخططات';
    public static $group = 'المشاريع';
    public static $description = 'المخططات';
    public static $icon = 'folder';
    public static $permission = 'platform.systems.buildings';
    public static $order = 5;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\BuildingPlan::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [

            Input::make('title')->title('العنوان')->required(),
            Input::make('slug')->title('slug')->required(),
            Relation::make('project_id')
                ->fromModel(Project::class, 'name')
                ->title('اختر المشروع'),
            TextArea::make('excerpt')
                ->title('نص مختصر')
                ->rows(6),
            Quill::make('body')->title('المحتوي')->required(),

            Picture::make('image')->title('صورة')->required(),

            Attach::make('plan_image')
                ->title('صورة المخطط')
                ->maxFiles(1)
                ->accept('image/*')
                ->help('يمكنك تحميل صورة واحدة فقط')
                ->horizontal(),

            Switcher::make('active')->title('حالة المبني')
                ->sendTrueOrFalse()
                ->value('1')
                ->placeholder('حالة المبني'),


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
            TD::make('title', 'عنوان المخطط')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($model) {
                    return $model->name;
                }),
            TD::make('product_id', 'اسم المشروع')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($model) {
                    return $model->project ? $model->project->name : '';
                }),
            TD::make('image', 'الصورة')
                ->render(function ($model) {
                    return '<a href="'.route('building_plan.index',$model->id).'"><img src="' . $model->image . '" alt="' . $model->name . '" style=" height: 100px;" /></a>';
                }),

            TD::make('type', 'النوع')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($model) {
                    return $model->type;
                }),
            TD::make('active', 'حالة المخطط')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($model) {
                    return $model->active ? 'مفعل' : 'غير مفعل';
                }),
            TD::make('id', 'رفع اكسل') 
                ->render(function ($model) {
                    return '<a href="'.route('building.uploadExcel',$model->id).'">رفع المباني</a>';
                }),
            // TD::make('created_at', 'Date of creation')
            //     ->render(function ($model) {
            //         return $model->created_at->toDateTimeString();
            //     }),

            // TD::make('updated_at', 'Update date')
            //     ->render(function ($model) {
            //         return $model->updated_at->toDateTimeString();
            //     }),
               
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
            Sight::make('title', 'اسم المخطط'),
            Sight::make('product_id', 'اسم المشروع'),
            Sight::make('image', 'الصورة')
                ->render(function ($model) {
                    return '<img src="' . $model->image . '" alt="' . $model->name . '" style=" height: 100px;" />';
                }),
            Sight::make('location', 'الموقع'),
            Sight::make('type', 'النوع'),
            Sight::make('active', 'حالة المخطط'),
            Sight::make('created_at', 'تاريخ الانشاء'),
            Sight::make('updated_at', 'تاريخ التحديث'),
        ];
    }
    public function actions(): array
    {
        return [

            
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
