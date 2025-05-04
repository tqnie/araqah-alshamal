@extends('platform::dashboard')

@section('title', 'تخطيط المباني')
@section('description', 'تخطيط المباني')

@section('navbar')
<style>
    .container {
        position: relative;
        margin: 20px;
        overflow: hidden;
        border: 1px solid #ccc;
        height: 80vh;
    }
    #canvas {
        border: 1px solid #ccc;
        margin: 20px auto;
        display: block;
    }
    .controls-panel, .building-form {
        position: fixed;
        top: 20px;
        background: white;
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        z-index: 100;
        max-height: 90vh;
        overflow-y: auto;
    }
    .controls-panel {
        left: 20px;
    }
    .building-form {
        right: 20px;
        max-width: 400px;
    }
    .tool-btn {
        margin: 5px;
        padding: 8px 16px;
        cursor: pointer;
    }
    .active {
        background-color: #007bff;
        color: white;
    }
    .building-form div {
        margin-bottom: 10px;
    }
    .building-form button {
        margin-top: 15px;
    }
</style>
@stop
{{-- street_view<direction<type<area<price<sale<building_number<building_plan_id --}}
@section('content')
<div class="text-center mt-5 mb-5">
    <div class="container">
        <canvas id="canvas"></canvas>
    </div>

    <div class="controls-panel">
        <h3>أدوات التحكم</h3>
        <button class="tool-btn" id="selectTool">تحديد</button>
        <button class="tool-btn" id="rectangleTool">مستطيل</button>
        <button class="tool-btn" id="deleteTool">حذف</button>

        <div>
            <label>الحالة:</label>
            <select id="building_sale"> 
                <option value="مباعة">مباعة</option>
                <option value="غير مباعة">غير مباعة</option>
                <option value="تحت الانشاء">تحت الانشاء</option>
            </select>
        </div>
        <div>
            <label>الشفافية:</label>
            <input type="range" id="opacity" min="0" max="100" value="30">
        </div>
    </div>

    <div class="building-form">
        <h3>إضافة مبنى جديد</h3>
        <form id="buildingForm">
            
            <div>
                <label>البلك:</label>
                <input type="number" id="building_bloc" />
            </div>
            <div>
                <label>القطعة:</label>
                <input type="number" id="building_number" /> 
            <div>
                <label>عرض الشارع:</label>
                <input type="number" id="street_view" />
            </div>
            <div>
                <label>المساحة :</label>
                <input type="number" id="building_area" />
            </div>
            <div>
                <label>الاتجاه:</label>
                <select id="building_direction"> 
                    <option value="شرقي">شرقي</option>
                    <option value="غربي">غربي</option>
                    <option value="شمالي">شمالي</option>
                    <option value="جنوبي">جنوبي</option>
                    <option value="جنوب شرقي">جنوب شرقي</option>
                    <option value="جنوب غربي">جنوب غربي</option>
                    <option value="شمالي شرقي">شمالي شرقي</option>
                    <option value="شمالي جنوبي">شمالي جنوبي</option>
                </select>
            </div>
            <div>
                <label>نوع المبنى:</label>
                <select id="building_type">
                    <option value="تجاري">تجاري</option>
                    <option value="سكني">سكني</option>
                    <option value="مكتبي">مكتبي</option>
                    <option value="فندقي">فندقي</option>
                </select>
            </div>
            <div>
                <label>السعر:</label>
                <input type="number" id="building_price" required>
            </div>
           
            <button type="submit">إضافة</button>
        </form>

        <div class="buildings-list">
            <h3>المباني المضافة</h3>
            <pre id="buildingsList"></pre>
        </div>
    </div>
</div>
@stop

@push('styles')
<style>
    /* Add additional custom styles here if needed */
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fabric@latest/dist/fabric.min.js"></script>

<script>
    // إعدادات الـCanvas
    const canvas = new fabric.Canvas('canvas', {
        width: window.innerWidth - 40,
        height: window.innerHeight - 40,
        selection: true
    });

   
    const buildings =@json($buildingPlan->buildings);

    // إضافة المباني إلى الـCanvas
    buildings.forEach(building => {
        const rect = new fabric.Rect({
            left: building.coordinates.left,
            top: building.coordinates.top,
            fill: building.type === 'مباعة' ? 'rgba(255, 0, 43, 0.5)' : 'rgba(0, 255, 0, 0.5)',
            width: building.coordinates.width,
            height: building.coordinates.height,
            selectable: false,
            hoverCursor: 'pointer'
        });

        rect.on('mousedown', function() {
            alert(`معلومات القطعة: ${building.name}`);
        });

        canvas.add(rect);
    });

    // تحميل الصورة الخلفية للمخطط
    fabric.Image.fromURL('{{$buildingPlan->pathImage->url}}', function(img) {
        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
            originX: 'left',
            originY: 'top',
            scaleX: 1,
            scaleY: 1
        });
    });

    // أدوات التحكم
    let currentTool = 'select';
    document.getElementById('selectTool').addEventListener('click', () => setTool('select'));
    document.getElementById('rectangleTool').addEventListener('click', () => setTool('rectangle'));
    document.getElementById('deleteTool').addEventListener('click', () => setTool('delete'));

    function setTool(tool) {
        currentTool = tool;
        document.querySelectorAll('.tool-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById(`${tool}Tool`).classList.add('active');

        canvas.isDrawingMode = false;
        canvas.selection = (tool === 'select');

        if (tool === 'delete') {
            const activeObject = canvas.getActiveObject();
            if (activeObject) {
                canvas.remove(activeObject);
                canvas.renderAll();
            }
        }
    }

    // تمكين التكبير والتصغير باستخدام عجلة الفأرة
    canvas.on('mouse:wheel', function(e) {
        let zoom = canvas.getZoom();
        let delta = e.e.deltaY;

        zoom = delta > 0 ? zoom * 1.1 : zoom / 1.1;
        zoom = Math.max(0.2, Math.min(zoom, 5));
        canvas.zoomToPoint({ x: e.e.offsetX, y: e.e.offsetY }, zoom);
        e.e.preventDefault();
    });

    // تمكين السحب لتحريك الخلفية
    let isDragging = false;
    let lastPosX, lastPosY;
    canvas.on('mouse:down', function(e) {
        if (currentTool === 'select' && e.e.button === 0) {
            isDragging = true;
            lastPosX = e.e.clientX;
            lastPosY = e.e.clientY;
        }
    });
//^s%s![3;3(10
    canvas.on('mouse:move', function(e) {
        if (isDragging && currentTool === 'select') {
            const deltaX = e.e.clientX - lastPosX;
            const deltaY = e.e.clientY - lastPosY;

            canvas.viewportTransform[4] += deltaX;
            canvas.viewportTransform[5] += deltaY;
            canvas.renderAll();

            lastPosX = e.e.clientX;
            lastPosY = e.e.clientY;
        }
    });

    canvas.on('mouse:up', function() {
        isDragging = false;
    });

    // رسم مستطيل عند اختيار أداة المستطيل
    let isDrawing = false;
    let currentShape = null;
    canvas.on('mouse:down', function(e) {
        if (currentTool === 'rectangle' && !isDrawing) {
            isDrawing = true;
            const pointer = canvas.getPointer(e.e);
            const color = document.getElementById('building_sale').value !== 'مباعة' ? '#00ff00' : '#ff0000';
            currentShape = new fabric.Rect({
                left: pointer.x,
                top: pointer.y,
                width: 0,
                height: 0,
                fill: color,
                opacity: document.getElementById('opacity').value / 100,
                selectable: true
            });
            canvas.add(currentShape);
        }
    });

    canvas.on('mouse:move', function(e) {
        if (isDrawing && currentTool === 'rectangle') {
            const pointer = canvas.getPointer(e.e);

            if (currentShape) {
                if (pointer.x < currentShape.left) currentShape.set({ left: pointer.x });
                if (pointer.y < currentShape.top) currentShape.set({ top: pointer.y });

                currentShape.set({
                    width: Math.abs(pointer.x - currentShape.left),
                    height: Math.abs(pointer.y - currentShape.top)
                });

                canvas.renderAll();
            }
        }
    });

    canvas.on('mouse:up', function() {
        isDrawing = false;
        if (currentShape) currentShape.setCoords();
    });

    // حفظ المباني
    document.getElementById('buildingForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const activeObject = canvas.getActiveObject();
        if (!activeObject) {
            alert('الرجاء تحديد شكل أولاً');
            return;
        }
        // building_bloc,building_number,street_view,building_direction,building_type,building_area,building_price,building_plan_id
    //    'building_plan_id',
    //     'building_number',
    //     'sale',
    //     'price',
    //     'area',
    //     'street_view',
    //     'direction',
    //     'type',
    //     'x',
    //     'y',
    //     'width',
    //     'height',
    //     'active'

     
        const building = {
            id: buildings.length + 1,
            name: document.getElementById('building_bloc').value,
            sale: document.getElementById('building_sale').value,
            building_number: document.getElementById('building_number').value,
            area: document.getElementById('building_area').value,
            street_view: document.getElementById('street_view').value,
            direction: document.getElementById('building_direction').value,
            type: document.getElementById('building_type').value,
            price: document.getElementById('building_price').value,
            building_plan_id: {{$buildingPlan->id}},
            
            coordinates: {
                left: activeObject.left,
                top: activeObject.top,
                width: activeObject.width * activeObject.scaleX,
                height: activeObject.height * activeObject.scaleY,
                angle: activeObject.angle
            }
        };
         
        window.axios.post('{{route("building.store",$buildingPlan->id)}}',building).then(response => {
            buildings.push(building);
            alert(response.data);
          //  this.reset();
        }).catch(error => {
                    callback(true, error);
                });


       
       // updateBuildingsList();
    
    });

    function updateBuildingsList() {
        document.getElementById('buildingsList').textContent = JSON.stringify(buildings, null, 2);
    }

    // تحديث حجم Canvas عند تغيير حجم النافذة
    window.addEventListener('resize', function() {
        canvas.setWidth(window.innerWidth - 40);
        canvas.setHeight(window.innerHeight - 40);
        canvas.renderAll();
    });
</script>
@endpush
