<?php

namespace App\Orchid\Resources;

use App\Models\BuildingPlan;
use Orchid\Crud\Resource;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class BuildingResource extends Resource
{

    /**
     * The displayable label of the resource.
     *
     * @var string
     */
    public static $label = 'المباني';
    public static $title = 'المباني';
    public static $group = 'المشاريع';
    public static $description = 'المباني';
    public static $icon = 'folder';
    public static $permission = 'platform.systems.buildings';
    public static $order = 5;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Building::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {

        return [

            Relation::make('building_plan_id')
                ->fromModel(BuildingPlan::class, 'title')
                ->title('المخطط'),
            Input::make('building_number')
                ->title('رقم المبني')
                ->required(),
            Select::make('sale')
                ->title('حالة البيع')
                ->options([
                    'غير مباعة'=> 'غير مباعة',
                    'مباعة' => 'مباعة',
                ])
                ->required(),
            Input::make('price')
                ->title('السعر')
                ->required(),
            Input::make('area')
                ->title('المساحة')
                ->required(),
            Input::make('street_view')
                ->title('واجهة الشارع')
                ->required(),
            Select::make('direction')
                ->title('الاتجاه')
                ->options([
                    'north' => 'شمال',
                    'south' => 'جنوب',
                    'east' => 'شرق',
                    'west' => 'غرب',
                ])
                ->required(),
            Select::make('type')
                ->title('النوع')
                ->options([
                    'residential' => 'سكني',
                    'commercial' => 'تجاري',
                    'industrial' => 'صناعي',
                ])
                ->required(),
            Input::make('x')
                ->title('الموقع X')
                ->required(),
            Input::make('y')
                ->title('الموقع Y')
                ->required(),
            Input::make('width')
                ->title('العرض')
                ->required(),
            Input::make('height')
                ->title('الارتفاع')
                ->required(),
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
            TD::make('id', 'ID'),
            TD::make('building_plan_id', 'رقم المخطط')
                ->render(function ($model) {
                    return $model->buildingPlan ? $model->buildingPlan->title : '';
                }),
            TD::make('building_number', 'رقم المبني')
                ->render(function ($model) {
                    return $model->building_number;
                }),
            TD::make('sale', 'حالة البيع')
                ->render(function ($model) {
                    return $model->sale ? 'مباعة' : 'غير مباعة';
                }),
            TD::make('price', 'السعر')
                ->render(function ($model) {
                    return $model->price;
                }),
            TD::make('area', 'المساحة')
                ->render(function ($model) {
                    return $model->area;
                }),
            TD::make('street_view', 'واجهة الشارع')
                ->render(function ($model) {
                    return $model->street_view;
                }),
            TD::make('created_at', 'تاريخ الإنشاء')
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
            Sight::make('building_plan_id', 'رقم المخطط'),
            Sight::make('building_number', 'رقم المبني'),
            Sight::make('sale', 'حالة البيع'),
            Sight::make('price', 'السعر'),
            Sight::make('area', 'المساحة'),
            Sight::make('street_view', 'واجهة الشارع'),
            Sight::make('direction', 'الاتجاه'),
            Sight::make('type', 'النوع'),
            Sight::make('x', 'الموقع X'),
            Sight::make('y', 'الموقع Y'),
            Sight::make('width', 'العرض'),
            Sight::make('height', 'الارتفاع'),
            Sight::make('active', 'حالة المبني'),
            Sight::make('created_at', 'تاريخ الإنشاء')
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
