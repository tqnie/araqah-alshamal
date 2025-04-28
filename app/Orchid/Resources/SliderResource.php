<?php

namespace App\Orchid\Resources;

use Orchid\Crud\Resource;
use Orchid\Screen\Fields\Attach;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\TD;
use App\Models\Slider;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Sight;

class SliderResource extends Resource
{

    public static $label = 'الشرائح';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model =  Slider::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        // ['title',
        // 'body',
        // 'url',
        // 'status',
        // 'image',
        // 'product_id', ]
        return [
            Input::make('title')
                ->title('العنوان')
                ->required()
                ->placeholder('عنوان السلايدر'), 
            TextArea::make('body')
                ->title('المحتوي')
                ->rows(6)
                ->placeholder('محتوي السلايدر'),
            Attach::make('image')
                ->title('صورة السلايدر')
                ->maxFiles(1)
                ->accept('image/*')
                ->help('يمكنك تحميل صورة واحدة فقط')
                ->horizontal(),
            Input::make('url')
                ->title('الرابط') 
                ->placeholder('رابط السلايدر'),
            Input::make('product_id')
                ->title('رقم المنتج') 
                ->placeholder('رقم المنتج'),
            Switcher::make('active')
                ->title('حالة السلايدر')
                ->sendTrueOrFalse()
                ->value(1)
                ->placeholder('مفعل')
                ->help('تفعيل السلايدر'),
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
            TD::make('title')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function (Slider $slider) {
                    return $slider->title;
                }),
            TD::make('image')
                ->render(function (Slider $slider) {
                    $image = $slider->pathImage; 
                    if (!$image) {
                        return '';
                    }
                    return '<img src="' . $image->url(). '" alt="' . $slider->title . '" style="height: 100px;" />';
                }),
             
            TD::make('active')
                ->render(function (Slider $slider) {
                    return $slider->active ? 'مفعل' : 'غير مفعل';
                }),
            TD::make('created_at', 'تاريخ الإنشاء')
                ->render(function (Slider $slider) {
                    return $slider->created_at->toDateTimeString();
                }),
            TD::make('updated_at', 'تاريخ التحديث')
                ->render(function (Slider $slider) {
                    return $slider->updated_at->toDateTimeString();
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
            Sight::make('image', 'صورة السلايدر')
                ->render(function (Slider $slider) {
                    $image = $slider->pathImage; 
                    if (!$image) {
                        return '';
                    } 
                    return '<img src="' . $image->url() . '" alt="' . $slider->title . '" style="height: 100px;" />';
                }),

            Sight::make('url',  'الرابط'),
            Sight::make('product_id', 'رقم المنتج'),
            Sight::make('active', 'حالة السلايدر')
                ->render(function (Slider $slider) {
                    return $slider->active ? 'مفعل' : 'غير مفعل';
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
