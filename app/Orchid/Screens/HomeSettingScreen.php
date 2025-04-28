<?php

namespace App\Orchid\Screens;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; 
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Screen;
use Orchid\Setting\Facades\Setting;
use Orchid\Support\Facades\Layout;

class HomeSettingScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
         return [
            'slider_title' =>  Setting::get('slider_title'),
            'slider_subtitle' =>  Setting::get('slider_subtitle'),
            'slider_desc' =>  Setting::get('slider_desc'),
            'slider_url' => Setting::get('slider_url'),
            
            'section1_title' => Setting::get('section1_title'),
            'section1_subtitle' => Setting::get('section1_subtitle'),
            'section1_desc' => Setting::get('section1_desc'),

            'section2_title' => Setting::get('section2_title'),
            'section2_desc' => Setting::get('section2_desc'),
            
            'section3_title' => Setting::get('section3_title'),
            'section3_desc' => Setting::get('section3_desc'),
        ];
    }
    

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'HomeSettingScreen';
    }
 /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::columns([
                Layout::rows([
                    Input::make('section2_title')
                        ->title('العنوان'),
                    Input::make('section2_desc')
                        ->title('العنوان الفرعي'),
                     
                ])->title('المنتجات'),
                Layout::rows([
                    Input::make('section1_title')
                        ->title('العنوان'),
                    Input::make('section1_subtitle')
                        ->title('العنوان الفرعي'),
                    Input::make('section1_url')
                        ->title(' رابط الزر'), 
                     
                ])->title('المدونة'),
            ]),
                Layout::columns([
                    Layout::rows([
                        Input::make('slider_title')
                            ->title('العنوان'),
                        Input::make('slider_subtitle')
                            ->title('العنوان الفرعي'),
                        // Picture::make('site_logo')
                        //     ->errorMaxSizeMessage("File size is too large")
                        //     ->errorTypeMessage("Invalid file type"),
                        TextArea::make('slider_desc')
                            ->title('الوصف')
                            ->rows(6),
                       
                        Input::make('slider_url')
                            ->title('رابط الزر'), 
                    ])->title('السلايدر'), 

            ]),
        ];
    }
    public function save(Request $request): void
    {
        Cache::flush();
        $settings = $request->only(
            'slider_title','slider_subtitle','slider_desc','slider_url',
            'section1_title','section1_subtitle','section1_desc',
            'section2_title','section2_desc', 
        );
        foreach ($settings as $key => $value) {
            Setting::set($key, $value ?? '');
        }
 
        Toast::info(__('Setting updated.'));
    }
}
