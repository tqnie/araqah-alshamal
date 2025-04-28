<?php 
use App\Models\Post; 
use App\Models\Page; 
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    
    
    public Page $page;  
    public function mount( )
    {
        $this->page = Page::where('slug', 'contact-us')->firstOrFail(); 
    }
}; ?>
  
    <x-slot name="header">
        {{$page->title}}
        <x-slot name="subheader">
             <li class="breadcrumb-item active">{{$page->title}}</li> 
        </x-slot>
    </x-slot> 
    <div>
        <section class="page-content">
            <div class="container">
                <div class="page-iamge"><img src=" {{$page->image??asset('assets/images/blog_iamge.png')}}" alt="{{$page->title}}" title="{{$page->title}}" /></div>
                {{-- <div class="page-text">
                    {!! $page->body !!}
                      </div> --}}
            </div>
        </section>
        <section class="page-contact">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-12 col-md-6">
                        <div class="content">
                            <div class="title">
                                <h3>تواصل معنا</h3>
                                <p>{!! $page->body !!}</p>
                            </div>
                            <div class="icons">
                                <div class="info-boxs">
                                    <div class="icon">
                                        <img src="{{asset("assets/images/icons/location.svg")}}" />
                                    </div>
                                    <div class="info-content">
                                        <p>العنوان</p>
                                        <b>{{setting('site_address')}}</b>
                                    </div>
                                </div>
                                <div class="info-boxs">
                                    <div class="icon">
                                        <img src="{{asset("assets/images/icons/call.svg")}}" />
                                    </div>
                                    <div class="info-content">
                                        <p>رقم الهاتف</p>
                                        <b>{{setting('mobile')}}</b>
                                    </div>
                                </div>
                                <div class="info-boxs">
                                    <div class="icon">
                                        <img src="{{asset("assets/images/icons/sms-tracking.svg")}}" />
                                    </div>
                                    <div class="info-content">
                                        <p>الايميل</p>
                                        <b>{{setting('mobile')}}</b>
                                    </div>
                                </div>
                            </div>
                            <div class="social">
                                <h3>تابعنا علي</h3>
                                <ul>
                                    <li><a href="{{setting('instagram')}}" target="_blank"><img src="{{asset("assets/images/icons/instagram.png")}}" /></a></li>
                                    <li><a href="{{setting('twitter')}}" target="_blank"><img src="{{asset("assets/images/icons/twitter.png")}}" /></a></li>
                                    <li><a href="{{setting('snapchat')}}" target="_blank"><img src="{{asset("assets/images/icons/snapchat.png")}}" /></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <livewire:widgets.contact-us-widget />
                    </div>
                </div>
            </div>
        </section>
    </div>
   
          
          
      
          
    