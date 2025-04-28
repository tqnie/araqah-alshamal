<?php 
use App\Models\Project; 
use Livewire\Volt\Component;

new class extends Component
{
    public $projects = [];
    public function mount()
    {
        Project::active()->with(['buildingPlans'])->get()->each(function ($project) { 
            $this->projects[] =  [
                'name' => $project->name,
                'slug' => $project->slug,
                'body' => $project->body,
                'image' => $project->image,
                'location' => $project->location,
                'type' => $project->type,
                'active' => $project->active,
                'buildingPlans'=> $project->buildingPlans->map(function ($plan) { 
                    return [
                        'title' => $plan->title,
                        'slug' => $plan->slug,
                        'excerpt' => $plan->excerpt,
                        'body' => $plan->body,
                        'image' => $plan->image,
                        'location' => $plan->location,
                        'plan_image' => $plan->pathImage()->find($plan->plan_image)?->url(),
                        'product_id' => $plan->product_id,
                        'type' => $plan->type,
                        'active' => $plan->active, 
                    ];
                }),
            ];
        });
    }
}; ?>


<section class="real_estate-shopping">
    @foreach ($projects as $project)
    <div class="container">
        <div class="sec-title">
            <div class="row justify-content-between">
                <div class="col">
                    <h3>{{$project['name']}}</h3>
                    <p>{!! $project['body'] !!}</p>
                </div>
                <div class="col-md-auto "><a href="{{route('project.show',$project['slug'])}}" class="btn btn-outline-dark">مشاهدة المزيد</a></div>
            </div>
        </div>
        <div class="real_estate">
            <div class="row">
                @foreach ($project['buildingPlans'] as $buildingPlan)
                <div class="col-12 col-md-4">
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title">{{$buildingPlan['title']}}</h5>
                        <p class="card-text">{{$buildingPlan['excerpt']}}</p>
                        <div class="card-link"><a href="{{route('building-plan.show',$buildingPlan['slug'])}}" class="">قرأة المزيد</a></div>
                      </div>
                      <div class="card-img"><img src="{{$buildingPlan['image']}}" class="card-img" alt="{{$buildingPlan['title']}}"></div>
                    </div>
                </div>
                @endforeach
                
                 
            </div>
        </div>
    </div> 
    @endforeach
   
</section>