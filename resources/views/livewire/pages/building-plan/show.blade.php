<?php 
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Http\Resources\BuildingPlanResource;
use App\Models\BuildingPlan;
use Artesaos\SEOTools\Facades\SEOMeta;

new #[Layout('layouts.app')] class extends Component
{   
    public $buildingPlan;
    public $pathImage;
    public $buildings = [];
    
    public function mount($slug)
    {
        // Fetch building plan with eager loaded relationships and counts
        $buildingPlan = BuildingPlan::where('slug', $slug)
            ->active()
            ->with(['buildings'])
            ->withCount([
                'buildings as count_buildings',
                'buildings as count_buildings_sold' => fn($q) => $q->where('sale', 'مباعة'),
                'buildings as count_buildings_nosold' => fn($q) => $q->where('sale', '!=', 'مباعة')
            ])
            ->firstOrFail();
        
        // Set SEO metadata
        SEOMeta::setTitle($buildingPlan->title);
        SEOMeta::setDescription($buildingPlan->excerpt);
        
        // Store path image for the map
        $this->pathImage = $buildingPlan->pathImage->url;
        
        // Transform building plan data using resource
        $this->buildingPlan = BuildingPlanResource::make($buildingPlan)->only([
            'id', 'title', 'image', 'excerpt', 'body',
            'count_buildings_nosold', 'count_buildings_sold',
            'slug', 'count_buildings', 'location',
            'building_image', 'project_id', 'type', 'active'
        ]);
        
        // Format buildings data for the interactive map
        $this->buildings = $buildingPlan->buildings->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->id,
                'sale' => $item->sale,
                'building_number' => $item->building_number,
                'area' => $item->area,
                'street_view' => $item->street_view,
                'direction' => $item->direction,
                'type' => $item->type,
                'price' => $item->price,
                'building_plan_id' => $item->building_plan_id,
                'coordinates' => [
                    'left' => $item->x,
                    'top' => $item->y,
                    'width' => $item->width,
                    'height' => $item->height,
                    'angle' => $item->angle ?? 0
                ]
            ];
        })->toArray();
    }
}; 
?>

<div>
    <x-slot name="header">
        {{$buildingPlan['title']}}
        <x-slot name="subheader">
            <li class="breadcrumb-item"><a href="{{route('projects.index')}}" wire:navigate>المشاريع</a></li>
            <li class="breadcrumb-item active">{{$buildingPlan['title']}}</li> 
        </x-slot>
    </x-slot> 
 
    <section class="marketing-plans first-plan">
        <div class="container">
            <!-- Section Header -->
            <div class="head">
                <h6 class="sup-title">فرصة استثمارية لا تفوت!</h6>
                <h3>{{$buildingPlan['title']}}</h3>
                <p>{{$buildingPlan['excerpt']}}</p>
            </div>
            
            <!-- Interactive Map Area -->
            <div class="marketing-plan-map">
                <div class="map-container" id="mapContainer">
                    <canvas id="mapCanvas"></canvas>
                </div>
            </div>
            
            <!-- Building Info Modal -->
            <div class="modal fade" id="buildingInfoModal" tabindex="-1" aria-labelledby="buildingInfoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="buildingInfoModalLabel">معلومات القطعة</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="plotDetails">
                            اضغط على قطعة للمزيد من المعلومات
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Building Plan Info -->
            <div class="row mt-4">
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
                                    <div class="status">الوحدات المتوفرة</div>
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
                                    <div class="status">غير مباع</div>
                                    <div class="quantity">أكثر من {{$buildingPlan['count_buildings_nosold']}} قطعة</div>
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
    max-width: 100%;
    overflow: hidden;
    margin-bottom: 30px;
}

.map-container {
    flex: 1;
    position: relative;
    overflow: hidden;
    border: 1px solid #ccc;
    background: #f5f5f5;
    min-height: 600px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

#buildingInfoModal .modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #eaeaea;
}

#buildingInfoModal .modal-title {
    font-weight: bold;
    color: #333;
}

#buildingInfoModal .modal-body {
    padding: 20px;
    line-height: 1.8;
}

#buildingInfoModal .property-info-row {
    display: flex;
    margin-bottom: 10px;
    border-bottom: 1px dashed #eee;
    padding-bottom: 8px;
}

#buildingInfoModal .property-label {
    flex: 0 0 120px;
    font-weight: bold;
    color: #555;
}

#buildingInfoModal .property-value {
    flex: 1;
}

#buildingInfoModal .badge-status {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    margin-top: 5px;
}

#buildingInfoModal .badge-sold {
    background-color: #dc3545;
    color: white;
}

#buildingInfoModal .badge-available {
    background-color: #28a745;
    color: white;
}

@media (max-width: 768px) {
    .map-container {
        min-height: 400px;
    }
    
    #buildingInfoModal .property-info-row {
        flex-direction: column;
    }
    
    #buildingInfoModal .property-label {
        margin-bottom: 4px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fabric@latest/dist/fabric.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if Bootstrap is available
    if (typeof bootstrap === 'undefined') {
        // Load Bootstrap JS if not available
        const bootstrapScript = document.createElement('script');
        bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js';
        bootstrapScript.integrity = 'sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4';
        bootstrapScript.crossOrigin = 'anonymous';
        document.head.appendChild(bootstrapScript);
        
        bootstrapScript.onload = function() {
            initializeMap();
        };
    } else {
        initializeMap();
    }
});

function initializeMap() {
    // Building data from server
    const buildings = @json($buildings);
    
    // Initialize canvas
    const canvas = new fabric.Canvas('mapCanvas', {
        selection: false,
        preserveObjectStacking: true
    });
    
    // State variables
    let scale = 1;
    let isDragging = false;
    let lastPosX, lastPosY;
    let activeInfoBox = null;
    let lastTouchDistance = 0;
    
    // Resize canvas to fit container
    function resizeCanvas() {
        const container = document.getElementById('mapContainer');
        if (container) {
            canvas.setWidth(container.clientWidth);
            canvas.setHeight(600);
            canvas.renderAll();
        }
    }
    
    // Initial canvas sizing
    resizeCanvas();
    
    // Load map background image
    function loadMapImage() {
        fabric.Image.fromURL('{{$pathImage}}', function(img) {
            // Calculate scaling to fit the canvas while maintaining aspect ratio
            const canvasWidth = canvas.getWidth();
            const canvasHeight = canvas.getHeight();
            
            const scaleX = canvasWidth / img.width;
            const scaleY = canvasHeight / img.height;
            const scale = Math.min(scaleX, scaleY);
            
            img.set({
                originX: 'left',
                originY: 'top',
                // scaleX: scale,
                // scaleY: scale,
                selectable: false
            });
            
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
            canvas.mapImage = img; // Store reference to the image
        }, { crossOrigin: 'anonymous' });
        
    }
    
    loadMapImage();
    
    // Draw buildings on the map
    function drawBuildings() {
        buildings.forEach(building => {
            // Create rectangle for each building
            const rect = new fabric.Rect({
                left: building.coordinates.left,
                top: building.coordinates.top,
                width: building.coordinates.width,
                height: building.coordinates.height,
                angle: building.coordinates.angle || 0,
                fill: building.sale === 'مباعة' ? 'rgba(255, 0, 43, 0.5)' : 'rgba(0, 255, 0, 0.5)',
                stroke: building.sale === 'مباعة' ? '#ff002b' : '#00b300',
                strokeWidth: 0,
                selectable: false,
                hoverCursor: 'pointer',
                rx: 4,
                ry: 4,
                objectCaching: false
            });
            
            // Add building data to the rectangle for reference
            rect.buildingData = building;
            
            // Add event listener for click/tap
            rect.on('mousedown', function(opt) {
                // Highlight the selected building
                this.set({
                    strokeWidth: 3,
                    stroke: '#0056b3'
                });
                canvas.renderAll();
                
                // Show building info in modal
                showBuildingInfo(this.buildingData, this);
                
                // Reset the highlight after a short delay
                setTimeout(() => {
                    this.set({
                        strokeWidth: 0,
                        stroke: this.buildingData.sale === 'مباعة' ? '#ff002b' : '#00b300'
                    });
                    canvas.renderAll();
                }, 1000); 
            });
            
            // Add hover effect
            rect.on('mouseover', function() {
                this.set({
                    strokeWidth: 2,
                    opacity: 0.8
                });
                canvas.renderAll();
            });
            
            rect.on('mouseout', function() {
                this.set({
                    strokeWidth: 1,
                    opacity: 1
                });
                canvas.renderAll();
            });
            
            canvas.add(rect);
            
            // Optional: Add tiny label for building number
            const label = new fabric.Text(building.building_number.toString(), {
                left: building.coordinates.left + building.coordinates.width / 2,
                top: building.coordinates.top + building.coordinates.height / 2,
                fontSize: 10,
                fill: '#fff',
                fontWeight: 'bold',
                originX: 'center',
                originY: 'center',
                selectable: false,
                evented: false
            });
            canvas.add(label);
        });
    }
    
    drawBuildings();
    
    // Display building information in modal
    function showBuildingInfo(building, rect) {
        // Create status badge class
        const statusClass = building.sale === 'مباعة' ? 'badge-sold' : 'badge-available';
        
        // Format the details in a more structured way for the modal
        document.getElementById('plotDetails').innerHTML = `
            <div class="building-info">
                <div class="property-info-row">
                    <div class="property-label">رقم القطعة:</div>
                    <div class="property-value">${building.building_number}</div>
                </div>
                <div class="property-info-row">
                    <div class="property-label">الحالة:</div>
                    <div class="property-value">${building.type || '-'}</div>
                </div>
                <div class="property-info-row">
                    <div class="property-label">المساحة:</div>
                    <div class="property-value">${building.area || '-'} م²</div>
                </div>
                <div class="property-info-row">
                    <div class="property-label">الشارع:</div>
                    <div class="property-value">${building.street_view || '-'}</div>
                </div>
                <div class="property-info-row">
                    <div class="property-label">الاتجاه:</div>
                    <div class="property-value">${building.direction || '-'}</div>
                </div>
                <div class="property-info-row">
                    <div class="property-label">السعر:</div>
                    <div class="property-value">${building.price || '-'}</div>
                </div>
                <div class="property-info-row">
                    <div class="property-label">النوع:</div>
                    <div class="property-value">
                        <span class="badge-status ${statusClass}">${building.sale || '-'}</span>
                    </div>
                </div>
            </div>
        `;
        
        // Set the modal title with building number
        document.getElementById('buildingInfoModalLabel').textContent = `معلومات القطعة ${building.building_number}`;
        
        // Show the modal
        const buildingModal = new bootstrap.Modal(document.getElementById('buildingInfoModal'));
        buildingModal.show();
        
        // Remove any existing info box on the canvas
        if (activeInfoBox) {
            canvas.remove(activeInfoBox);
            activeInfoBox = null;
        }
    }
    
    // Mouse wheel zoom
    canvas.on('mouse:wheel', function(opt) {
        const delta = opt.e.deltaY;
        let zoom = canvas.getZoom();
        zoom *= 0.999 ** delta;
        
        // Limit zoom level
        zoom = Math.min(Math.max(0.2, zoom), 5);
        
        canvas.zoomToPoint({ x: opt.e.offsetX, y: opt.e.offsetY }, zoom);
        opt.e.preventDefault();
        opt.e.stopPropagation();
    });
    
    // Pan with mouse drag
    canvas.on('mouse:down', function(opt) {
        if (opt.e.button === 0) { // Left mouse button
            const pointerType = opt.e.type.includes('touch') ? 'touch' : 'mouse';
            if (pointerType === 'mouse') {
                isDragging = true;
                lastPosX = opt.e.clientX;
                lastPosY = opt.e.clientY;
                canvas.selection = false;
            }
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
            canvas.requestRenderAll();
        }
    });
    
    canvas.on('mouse:up', function() {
        isDragging = false;
        canvas.selection = true;
    });
    
    // Touch events for mobile
    canvas.upperCanvasEl.addEventListener('touchstart', function(e) {
        if (e.touches.length === 1) {
            // Start dragging
            isDragging = true;
            lastPosX = e.touches[0].clientX;
            lastPosY = e.touches[0].clientY;
            
            // Check if touch is on a building
            const pointer = canvas.getPointer(e.touches[0]);
            const objects = canvas.getObjects();
            
            for (let i = 0; i < objects.length; i++) {
                if (objects[i].type === 'rect' && objects[i].containsPoint(pointer)) {
                    isDragging = false; // Prevent panning when tapping on a building
                    
                    // Highlight the selected building
                    objects[i].set({
                        strokeWidth: 3,
                        stroke: '#0056b3'
                    });
                    canvas.renderAll();
                    
                    // Show building info in modal
                    showBuildingInfo(objects[i].buildingData, objects[i]);
                    
                    // Reset the highlight after a short delay
                    setTimeout(() => {
                        objects[i].set({
                            strokeWidth: 1,
                            stroke: objects[i].buildingData.sale === 'مباعة' ? '#ff002b' : '#00b300'
                        });
                        canvas.renderAll();
                    }, 1000);
                    
                    e.preventDefault();
                    break;
                }
            }
        } else if (e.touches.length === 2) {
            // Store initial distance for pinch zoom
            isDragging = false;
            lastTouchDistance = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
            e.preventDefault();
        }
    }, { passive: false });
    
    canvas.upperCanvasEl.addEventListener('touchmove', function(e) {
        if (isDragging && e.touches.length === 1) {
            // Pan the map
            const vpt = canvas.viewportTransform;
            vpt[4] += e.touches[0].clientX - lastPosX;
            vpt[5] += e.touches[0].clientY - lastPosY;
            canvas.setViewportTransform(vpt);
            lastPosX = e.touches[0].clientX;
            lastPosY = e.touches[0].clientY;
            e.preventDefault();
        } else if (e.touches.length === 2) {
            // Pinch zoom
            const currentDistance = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
            
            if (lastTouchDistance > 0) {
                const scaleFactor = currentDistance / lastTouchDistance;
                let zoom = canvas.getZoom() * scaleFactor;
                
                // Limit zoom level
                zoom = Math.min(Math.max(0.2, zoom), 5);
                
                // Center point between touches
                const pinchCenter = {
                    x: (e.touches[0].clientX + e.touches[1].clientX) / 2,
                    y: (e.touches[0].clientY + e.touches[1].clientY) / 2
                };
                
                canvas.zoomToPoint(pinchCenter, zoom);
            }
            
            lastTouchDistance = currentDistance;
            e.preventDefault();
        }
    }, { passive: false });
    
    canvas.upperCanvasEl.addEventListener('touchend', function(e) {
        if (e.touches.length === 0) {
            isDragging = false;
            lastTouchDistance = 0;
            
            // Ensure background image is still present
            checkAndRestoreBackground();
        }
    });
    
    // Ensure background image is present
    function checkAndRestoreBackground() {
        if (!canvas.backgroundImage && canvas.mapImage) {
            canvas.setBackgroundImage(canvas.mapImage, canvas.renderAll.bind(canvas));
        }
    }
    
    // Periodic check for background image
    setInterval(checkAndRestoreBackground, 2000);
    
    // Handle window resize
    window.addEventListener('resize', function() {
        resizeCanvas();
        checkAndRestoreBackground();
    });
    
    // Add reset view button and controls
    function addMapControls() {
        // Create control container
        const controlsContainer = document.createElement('div');
        controlsContainer.className = 'map-controls';
        controlsContainer.style.position = 'absolute';
        controlsContainer.style.bottom = '10px';
        controlsContainer.style.right = '10px';
        controlsContainer.style.zIndex = '100';
        controlsContainer.style.display = 'flex';
        controlsContainer.style.flexDirection = 'column';
        controlsContainer.style.gap = '5px';
        
        // Reset button
        const resetButton = document.createElement('button');
        resetButton.innerText = 'إعادة ضبط العرض';
        resetButton.className = 'btn btn-sm btn-light';
        resetButton.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
        resetButton.addEventListener('click', function() {
            canvas.setViewportTransform([1, 0, 0, 1, 0, 0]);
            canvas.renderAll();
        });
        
        // Zoom in button
        const zoomInButton = document.createElement('button');
        zoomInButton.innerText = '+';
        zoomInButton.className = 'btn btn-sm btn-light';
        zoomInButton.style.width = '40px';
        zoomInButton.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
        zoomInButton.addEventListener('click', function() {
            let zoom = canvas.getZoom();
            zoom = Math.min(zoom * 1.2, 5);
            
            const center = {
                x: canvas.getWidth() / 2,
                y: canvas.getHeight() / 2
            };
            
            canvas.zoomToPoint(center, zoom);
        });
        
        // Zoom out button
        const zoomOutButton = document.createElement('button');
        zoomOutButton.innerText = '-';
        zoomOutButton.className = 'btn btn-sm btn-light';
        zoomOutButton.style.width = '40px';
        zoomOutButton.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
        zoomOutButton.addEventListener('click', function() {
            let zoom = canvas.getZoom();
            zoom = Math.max(zoom / 1.2, 0.2);
            
            const center = {
                x: canvas.getWidth() / 2,
                y: canvas.getHeight() / 2
            };
            
            canvas.zoomToPoint(center, zoom);
        });
        
        // Add legend
        const legendContainer = document.createElement('div');
        legendContainer.className = 'map-legend';
        legendContainer.style.position = 'absolute';
        legendContainer.style.top = '10px';
        legendContainer.style.right = '10px';
        legendContainer.style.zIndex = '100';
        legendContainer.style.backgroundColor = 'white';
        legendContainer.style.padding = '8px';
        legendContainer.style.borderRadius = '4px';
        legendContainer.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
        
        legendContainer.innerHTML = `
            <div style="font-size: 12px; font-weight: bold; margin-bottom: 5px;">دليل الخريطة</div>
            <div style="display: flex; align-items: center; margin-bottom: 4px;">
                <div style="width: 15px; height: 15px; background: rgba(0, 255, 0, 0.5); border: 1px solid #00b300; margin-left: 5px;"></div>
                <span style="font-size: 12px;">متاح</span>
            </div>
            <div style="display: flex; align-items: center;">
                <div style="width: 15px; height: 15px; background: rgba(255, 0, 43, 0.5); border: 1px solid #ff002b; margin-left: 5px;"></div>
                <span style="font-size: 12px;">مباع</span>
            </div>
        `;
        
        // Add buttons to container
        controlsContainer.appendChild(zoomInButton);
        controlsContainer.appendChild(zoomOutButton);
        controlsContainer.appendChild(resetButton);
        
        // Add containers to map
        document.getElementById('mapContainer').appendChild(controlsContainer);
        document.getElementById('mapContainer').appendChild(legendContainer);
    }
    
    addMapControls();
}
</script>
@endpush