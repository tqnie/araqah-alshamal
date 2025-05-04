<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

<livewire:widgets.slider-widget />
   
<section class="search-home">
    <div class="container">
        <div class="sec-title">
            <h3>بحث</h3>
            <p>هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى</p>
        </div>
        <form class="search-form d-flex">
            <div class="col-md-auto content-text">
                <h6>عراقة الشمال</h6>
                <p>لدينا أكتر من300.000 قطعة أرض</p>
            </div>
            <div class="col content-input text-center"><input class="form-control me-2" type="search" placeholder="بحث" aria-label="Search"></div>
            <div class="col content-button col-lg-2"><button class="btn btn-primary" type="submit"><i aria-hidden="true" class="fa fa-search"></i><span>بحث الآن</span></button></div>
        </form>
    </div>
</section>
  
<livewire:widgets.projects-widget />
<livewire:widgets.blogs-widget />



<section class="video-home">
    <div class="container">
        <div class="video-title">
            <div class="row">
                <div class="col-12 col-lg-5">
                    <h4>{{setting('slider_title')}}</h4>
                </div>
                <div class="col">
                    <div class="video-sub_title">
                        <p>{{setting('slider_desc')}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="video-container">
            <video id="myVideo" poster="poster.jpg">
                <source src="{{asset(setting('slider_url')??'assets/images/video.mp4')}}" type="video/mp4">
                متصفحك لا يدعم تشغيل الفيديو.
            </video>
            <button class="play-button" id="playBtn">▶</button>
        </div>			
    </div> 
</section> 
  
</x-app-layout>
