<?php 
use App\Models\Slider; 
use Livewire\Volt\Component;

new class extends Component
{
    public $slides = [];
    public function mount()
    {
        Slider::active()->get()->each(function ($slide) { 
            $this->slides[] =  [
                'title' => $slide->title,
                'body' => $slide->body,
                'url' => $slide->url, 
                'image' =>$slide->pathImage? $slide->pathImage->url():null,
            ];
        });
    }
}; ?>


<section class="first-area after-header" id="first">
    <div class="first-slider owl-carousel" style="direction: ltr;">
        @foreach ($slides as $slide)
        <div class="single-screen">
            <img src="{{$slide['image']}}" alt="{{$slide['title']}}">
            <div class="slide-content">
                <div class="slide-text">
                    <h2>{{$slide['title']}}</h2>
                    <p>{{$slide['body']}}</p>
                </div>
            </div>
        </div>
        @endforeach  
    </div>
</section>