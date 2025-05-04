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
    public   $buildingPlan;
    public $pathImage;
    public $buildings = [];
    public function mount($slug)
    {
        $buildingPlan=BuildingPlan::where('slug', $slug)->active()->with(['buildings'])->withCount(['buildings as count_buildings'])->firstOrFail();
        SEOMeta::setTitle($buildingPlan->title);
        SEOMeta::setDescription($buildingPlan->excerpt);
        
        $this->pathImage = $buildingPlan->pathImage->url;
        $this->buildingPlan = BuildingPlanResource::make($buildingPlan)->only([
        'id',
        'title',
        'image',
        'excerpt', 
        'body',
        'slug', 
        'count_buildings',
        'location',
        'building_image',
        'project_id',
        'type',
        'active']);
        
        $buildingPlan = BuildingPlan::find($id);
        $this->buildings = [];
        foreach($buildingPlan->buildings as $item){ 
            $this->buildings[]=  [
                'id'=> $item->id,
                'name'=> $item->id,
                'sale'=> $item->sale,
                'building_number'=> $item->building_number,
                'area'=> $item->area,
                'street_view'=> $item->street_view,
                'direction'=> $item->direction,
                'type'=> $item->type,
                'price'=> $item->price,
                'building_plan_id'=> $item->building_plan_id, 
                'coordinates'=> [
                    'left'=>$item->x,
                    'top'=>$item->y,
                    'width'=>$item->width ,
                    'height'=>$item->height ,
                    'angle'=>$item->angle
                ]
                ];
        };

         // $this->buildings =$project->buildings;
     
    }
    
}; ?>
<div>
    <x-slot name="header">
        {{$buildingPlan['title']}}
        <x-slot name="subheader">
            <li class="breadcrumb-item"><a href="{{route('projects.index')}}" wire:navigate> المشاريع  </a></li>
            <li class="breadcrumb-item active">{{$buildingPlan['title']}}</li> 
        </x-slot>
    </x-slot> 
 
    <section class="marketing-plans  first-plan  ">
        <div class="container">
            <div class="head">
                <h6 class="sup-title">فرصة استثمارية لا تفوت!</h6>
                <h3>{{$buildingPlan['title']}}</h3>
                <p>{{$buildingPlan['excerpt']}}</p>
            </div>
            <div class="marketing-plan-map">
               
                <div class="map-container" id="mapContainer">
                    <canvas id="mapCanvas"></canvas>
                </div>
                <div class="plot-info">
                    <h3>معلومات القطعة</h3>
                    <div id="plotDetails" style="margin-top: 20px; font-size: 16px;">اضغط على قطعة للمزيد من المعلومات</div>
                </div>
                
                
                {{-- <div class="controls">
                    <button class="button" onclick="zoomIn()">تكبير +</button>
                    <button class="button" onclick="zoomOut()">تصغير -</button>
                    <button class="button" onclick="resetZoom()">إعادة ضبط</button>
                    <div class="plot-info">
                        <h3>معلومات القطعة</h3>
                        <div id="plotDetails">اضغط على قطعة للمزيد من المعلومات</div>
                    </div>
                </div> --}}
                {{-- <img src="{{$buildingPlan['image']}}" alt="{{$buildingPlan['title']}}"  title="{{$buildingPlan['title']}}" /> --}}
            </div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="short-des">
                        <div class="plan-number">
                            <span>1</span>
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
                                    <div class="status">تجاري</div>
                                    <div class="quantity">أكثر من {{$buildingPlan['count_buildings']}} قطعة</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="statistic">
                                    <span class="badge bg-sold"></span>
                                    <div class="status">مباع</div>
                                    <div class="quantity">أكثر من {{$buildingPlan['count_buildings']}} قطعة</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="statistic">
                                    <span class="badge bg-unavailable"></span>
                                    <div class="status">غير متاح</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section> 

  
 
</div> 
@push('styles')
<style>
     .marketing-plan-map {
            display: flex;
            gap: 20px;
            max-width: 100%;
            overflow: hidden;
        }

        .map-container {
            flex: 1;
            position: relative;
            overflow: hidden;
            border: 1px solid #ccc;
            background: #f5f5f5;
            min-height: 600px;
        }

        .controls {
            width: 315px;
            padding: 5px;
            background: #f5f5f5;
            border-radius: 8px;
        }

        .button {
            padding: 8px 15px;
            margin: 5px;
            cursor: pointer;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }

        .plot-info {
            margin-top: 20px;
            padding: 10px;
            background: white;
            border-radius: 4px;
        }
</style>
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fabric@latest/dist/fabric.min.js"></script>
    <script>
        

        const buildings =  @json($buildings);
        const canvas = new fabric.Canvas('mapCanvas', {
        selection: false // منع تحديد العناصر العشوائي
    });

    let scale = 1;
    let isDragging = false;
    let lastPosX, lastPosY;
    let activeInfoBox = null;

    function resizeCanvas() {
        canvas.setWidth(window.innerWidth - 40);
        canvas.setHeight(window.innerHeight - 40);
        canvas.renderAll();
    }
    resizeCanvas();

    // تحميل صورة الخريطة
    fabric.Image.fromURL('{{$pathImage}}', function(img) {
        img.set({
            originX: 'left',
            originY: 'top',
            selectable: false
        });
        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
    }, { crossOrigin: 'anonymous' });

    // رسم الوحدات (مربعات)
    buildings.forEach(building => {
        const rect = new fabric.Rect({
            left: building.coordinates.left,
            top: building.coordinates.top,
            width: building.coordinates.width,
            height: building.coordinates.height,
            angle: building.coordinates.angle || 0,
            fill: building.type === 'مباعة' ? 'rgba(255, 0, 43, 0.5)' : 'rgba(0, 255, 0, 0.5)',
            selectable: false,
            hoverCursor: 'pointer'
        });

        rect.buildingData = building; // نحفظ بيانات المبنى داخل المستطيل نفسه

        rect.on('mousedown', function(e) {
            const building = this.buildingData;

            // تحديث بيانات الوحدة أسفل الخريطة
            document.getElementById('plotDetails').innerHTML = `
                <strong>رقم القطعة:</strong> ${building.name}<br>
                <strong>الحالة:</strong> ${building.type}<br>
                <strong>المساحة:</strong> ${building.space} م²
            `;

            // حذف أي مربع معلومات سابق
            if (activeInfoBox) {
                canvas.remove(activeInfoBox);
                activeInfoBox = null;
            }

            // إنشاء مربع معلومات جديد
            const infoText = new fabric.Text(`${building.name}\n${building.type}\n${building.space}م²`, {
                fontSize: 14,
                fill: 'white',
                textAlign: 'center',
                originX: 'center',
                originY: 'center'
            });

            const background = new fabric.Rect({
                width: infoText.width + 20,
                height: infoText.height + 20,
                fill: 'rgba(0, 0, 0, 0.7)',
                rx: 8,
                ry: 8,
                originX: 'center',
                originY: 'center'
            });

            const group = new fabric.Group([background, infoText], {
                left: rect.left + rect.width / 2,
                top: rect.top - 30,
                selectable: false,
                evented: false
            });

            activeInfoBox = group;
            canvas.add(group);
            canvas.bringToFront(group);
        });

        canvas.add(rect);
    });

    // تكبير وتصغير بعجلة الماوس
    canvas.on('mouse:wheel', function(opt) {
        let delta = opt.e.deltaY;
        let zoom = canvas.getZoom();
        zoom *= 0.999 ** delta;

        if (zoom > 5) zoom = 5;
        if (zoom < 0.2) zoom = 0.2;

        canvas.zoomToPoint({ x: opt.e.offsetX, y: opt.e.offsetY }, zoom);
        opt.e.preventDefault();
        opt.e.stopPropagation();
    });

    // تحريك الخريطة بالسحب
    canvas.on('mouse:down', function(opt) {
        if (opt.e.button === 0) { // الزر الأيسر للفأرة
            isDragging = true;
            lastPosX = opt.e.clientX;
            lastPosY = opt.e.clientY;
        }
    });

    canvas.on('mouse:move', function(opt) {
        if (isDragging) {
            const e = opt.e;
            const vpt = canvas.viewportTransform;
            vpt[4] += e.clientX - lastPosX;
            vpt[5] += e.clientY - lastPosY;
            canvas.setViewportTransform(vpt);
            lastPosX = e.clientX;
            lastPosY = e.clientY;
        }
    });

    canvas.on('mouse:up', function(opt) {
        isDragging = false;
    });

    window.addEventListener('resize', resizeCanvas);

    </script>
@endpush
   {{-- <section class="land_details-content">
		<div class="container">
			<div class="row">
				<div class="col-12 col-md-6">
					<div class="land_gallery">
						<div class="gallery">
							<div class="master-img">
								<img src="{{$buildingPlan['image']}}" alt="">
							</div>

							<div class="small-images">
								<img src="{{asset("assets/images/land_gallery-1.png")}}" class="selected" alt="">
								<img src="{{asset("assets/images/land_gallery-2.png")}}" alt="">
								<img src="{{asset("assets/images/land_gallery-3.png")}}" alt="">
								<img src="{{asset("assets/images/land_gallery-4.png")}}" alt="">
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div class="land_details">
						<h2>{{$buildingPlan['title']}}</h2>
						<p class="description">{!! $buildingPlan['body'] !!}</p>
						
						<ul class="more_details">
							<li>
								<p>بحجم</p>
								<div><img src="{{asset("assets/images/icons/size.svg")}}" /> <span>4,230 Sq. Ft.</span></div>
							</li>
							<li>
								<p>العنوان</p>
								<div><img src="{{asset("assets/images/icons/gmap.svg")}}" /> <span>{{$buildingPlan['location']}}</span></div>
							</li>
							<li>
								<p>السعر</p>
								<div><img src="{{asset("assets/images/icons/rsa.svg")}}" /> <span>55,000,000 ر.س</span></div>
							</li>
						</ul>
						
						<hr />
						
						<a class="btn btn-download" href="file/land.pdf" target="_blank" download><div class="text"><img src="{{asset("assets/images/icons/vscode-icons_file-type-pdf2.svg")}}"/> تفاصيل الأرض. pdf</div><img src="{{asset("assets/images/icons/download.svg")}}" /></a>
					</div>
				</div>
			</div>
		</div>
		<div class="land_advantages">
			<div class="container">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="attachments">
							<h3>المرافق</h3>
							<div class="row">
								<div class="col-12 col-sm-6">
									<div class="attachment"><img src="{{asset("assets/images/icons/attachments-icon.svg")}}" /> الامن علي مدار 24 ساعة</div>
								</div>
								<div class="col-12 col-sm-6">
									<div class="attachment"><img src="{{asset("assets/images/icons/attachments-icon.svg")}}" /> الانترنت ذات النطاق العالي</div>
								</div>
								<div class="col-12 col-sm-6">
									<div class="attachment"><img src="{{asset("assets/images/icons/attachments-icon.svg")}}" /> التخلص من القمامة</div>
								</div>
								<div class="col-12 col-sm-6">
									<div class="attachment"><img src="{{asset("assets/images/icons/attachments-icon.svg")}}" /> الحدائق</div>
								</div>
								<div class="col-12 col-sm-6">
									<div class="attachment"><img src="{{asset("assets/images/icons/attachments-icon.svg")}}" /> خدمات التجزئة</div>
								</div>
								<div class="col-12 col-sm-6">
									<div class="attachment"><img src="{{asset("assets/images/icons/attachments-icon.svg")}}" /> مركز اجتماعي</div>
								</div>
								<div class="col-12 col-sm-6">
									<div class="attachment"><img src="{{asset("assets/images/icons/attachments-icon.svg")}}" /> منطقة لعب أطفال</div>
								</div>
								<div class="col-12 col-sm-6">
									<div class="attachment"><img src="{{asset("assets/images/icons/attachments-icon.svg")}}" /> موقف سيارات</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="google_map">
							<h3>ماذا يوجد في الجوار</h3>
							<div class="iframe_embed">
								<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d463879.2652780794!2d47.1027217580867!3d24.724931560961956!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f03890d489399%3A0xba974d1c98e79fd5!2z2KfZhNix2YrYp9i2INin2YTYs9i52YjYr9mK2Kk!5e0!3m2!1sar!2seg!4v1666414769031!5m2!1sar!2seg" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section> --}}