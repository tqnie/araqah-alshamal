<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;
use App\Models\Project; 

new class extends Component
{

    public $projects; 
    public function mount()
    {
        $this->projects=Project::with(['buildingPlans'=>function($query){
            $query->active()->with(['buildings'])->withCount(['buildings as count_buildings']);
        }])->get(); 
       
    
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>
<header id="header-scroll">
    <div class="container">
        <div class="header-content">
            <div class="row">
                <div class="col-md-2 col-sm-4 col-xs-12">
                    <button id="nav-trigger" class="nav-trigger ">
                        <i class="fa fa-bars"></i>
                    </button>
                    <div class="logo"><a class="wow fadeInRightBig" href="{{route('home')}}" wire:navigate title="عراقة الشمال"><img src="{{asset('assets/images/logo.png')}}" alt="عراقة الشمال" title="عراقة الشمال"></a></div>
                </div>
                <div class="col-md-10 col-sm-8 d-none d-sm-block">
                    <div class="head-links">
                        <ul>
                            <li class="nav-item"><a href="{{route('home')}}" wire:navigate class="">الرئيسية</a></li>
                            <li class="nav-item"><a href="{{route('page.show','about-us')}}" class="">من نحن</a></li>
                            <li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                التسويق العقاري
                              </a>
                              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @foreach ($projects as $project)
                                    <li><a class="dropdown-item" href="{{route('project.show',$project->slug)}}">{{$project->name}}</a></li>
                                @endforeach
                                 
                              </ul>
                            </li>
                            <li class="nav-item"><a href="{{route('page.show','real_estate_development')}}" class="">التطوير العقاري</a></li>
                            <li class="nav-item"><a href="{{route('page.show','raw_land_development')}}" class="">تطوير الأراضي الخام</a></li>
                        </ul>
                        <div class="btns-head">
                            <a href="{{route('page.show','interest')}}" class="btn btn-outline-light">سجل اهتمامك</a>
                            <a href="{{route('page.contact-us')}}" wire:navigate  class="btn btn-primary">اتصل بنا</a>
                        </div>
                    </div>
                </div>
                <div id="main_menu">
                    <ul>
                        <li><a href="{{route('home')}}" class="">الرئيسية</a></li>
                        <li><a href="{{route('page.show','about-us')}}" class="">من نحن</a></li>
                        <li><a href="real_estate_marketing.html" class="">التسويق العقاري</a></li>
                        <li><a href="{{route('page.show','real_estate_development')}}" class="">التطوير العقاري</a></li>
                        <li><a href="{{route('page.show','raw_land_development')}}" class="">تطوير الأراضي الخام</a></li>
                        <li><a href="{{route('page.show','interest')}}" class="">سجل اهتمامك</a></li>
                        <li><a href="{{route('page.contact-us')}}" class="">اتصل بنا</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

