<?php 

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Http\Resources\BuildingPlanResource;
use App\Http\Resources\ProjectResource;
use App\Models\BuildingPlan;
use App\Models\Project; 
use Artesaos\SEOTools\Facades\SEOMeta;


new #[Layout('layouts.app')] class extends Component
{   
    public $project;
 
    public $buildingPlans = [];
    public function mount($slug)
    {
        $project=Project::where('slug', $slug)->with(['buildingPlans'=>function($query){
            $query->active()->with(['buildings'])->withCount(['buildings as count_buildings','buildings as count_buildings_sold'=>fn($q)=>$q->where('sale','مباعة'),'buildings as count_buildings_nosold'=>fn($q)=>$q->where('sale','!=','مباعة')]);
        }])->firstOrFail();
        SEOMeta::setTitle($project->name);
        SEOMeta::setDescription($project->excerpt);
        $this->project = ProjectResource::make($project)->all();
        $this->buildingPlans =$project->buildingPlans;
     
    }
    
}; ?>
<div>
<livewire:widgets.slider-widget />
@foreach ($buildingPlans as $buildingPlan)
<section class="marketing-plans @if($loop->index==0) first-plan  @elseif($loop->index==1) second-plan @elseif($loop->index==2) third-plan @endif">
    <div class="container">
        <div class="head">
            <h6 class="sup-title">فرصة استثمارية لا تفوت!</h6>
            <h3>{{$buildingPlan['title']}}</h3>
            <p>{{$buildingPlan['excerpt']}}</p>
        </div>
        <div class="marketing-plan-map">
            <a href="{{route('building-plan.show',$buildingPlan['slug'])}}"><img src="{{$buildingPlan['image']}}" alt="{{$buildingPlan['title']}}"  title="{{$buildingPlan['title']}}" /></a>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="short-des">
                    <div class="plan-number">
                        <span>{{$loop->index+1}}</span>
                    </div>
                    <h4>{{$buildingPlan['title']}}</h4>
                    <p>{{$buildingPlan['excerpt']}}</p>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="statistics-plan">
                    <div class="row">
                        <div class="col-12">
                            <div class="statistic">
                                <span class="badge bg-commercial"></span>
                                <div class="status">عدد الوحدات</div>
                                <div class="quantity">أكثر من {{$buildingPlan['count_buildings']}} قطعة</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="statistic">
                                <span class="badge bg-sold"></span>
                                <div class="status">مباع</div>
                                <div class="quantity">أكثر من {{$buildingPlan['count_buildings_sold']}} قطعة</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="statistic">
                                <span class="badge bg-unavailable"></span>
                                <div class="status">غير متاح</div>
                                <div class="quantity">أكثر من {{$buildingPlan['count_buildings_nosold']}} قطعة</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
@endforeach


 
</div>