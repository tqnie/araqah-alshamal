<?php

namespace App\Orchid\Resources;

use App\Models\Contact;
use Orchid\Crud\Resource;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class ContactUsResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Contact::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
         return [
            Input::make('subject')
                ->title('الموضوع')
                ->placeholder('ادخل الموضوع هنا'),
            Input::make('name')
                ->title('الاسم')
                ->placeholder('ادخل الاسم هنا'),
            Input::make('email')    
                ->title('البريد الالكتروني')
                ->placeholder('ادخل البريد الالكتروني هنا'),
            Input::make('mobile')
                ->title('رقم الهاتف')
                ->placeholder('ادخل رقم الهاتف هنا'),
            TextArea::make('body')
                ->title('المحتوي')
                ->placeholder('ادخل المحتوي هنا'),
            Select::make('status')
                ->title('الحالة')
                ->options([
                    'NEW' => 'جديد',
                    'IN_PROGRESS' => 'قيد المعالجة',
                    'CLOSED' => 'مغلق',
                ])
                ->empty('اختر الحالة'),
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
            TD::make('subject', 'الموضوع'),
            TD::make('name', 'الاسم'),
            TD::make('email', 'البريد الالكتروني'),
            TD::make('mobile', 'رقم الهاتف'),
            TD::make('status', 'الحالة')
                ->render(function ($model) {
                    return $model->status == 'NEW' ? 'جديد' : ($model->status == 'IN_PROGRESS' ? 'قيد المعالجة' : 'مغلق');
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
            // Add any additional fields you want to display in the legend
            // For example:
            Sight::make('id'),
            Sight::make('subject', 'الموضوع'),
            Sight::make('name', 'الاسم'),
            Sight::make('email', 'البريد الالكتروني'),
            Sight::make('mobile', 'رقم الهاتف'),
            Sight::make('body', 'المحتوي'),
            Sight::make('status', 'الحالة')
                ->render(function ($model) {
                    return $model->status == 'NEW' ? 'جديد' : ($model->status == 'IN_PROGRESS' ? 'قيد المعالجة' : 'مغلق');
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
        return [
          
             
        ];
    }
}
