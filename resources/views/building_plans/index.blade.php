@extends('platform::dashboard')

@section('title', 'تخطيط المباني')
@section('description', 'نظام إدارة وتخطيط المباني')

@push('head')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.rtl.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
<style>
    :root {
        --primary-color: #3490dc;
        --secondary-color: #38c172;
        --danger-color: #e3342f;
        --warning-color: #ffed4a;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --border-color: #dee2e6;
    }

    .building-layout-container {
        position: relative;
        margin: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        height: 80vh;
        background-color: var(--light-color);
    }

    .canvas-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    #canvas {
        border-radius: 4px;
        transition: all 0.3s ease;
    }
#controlsPanel,#buildingForm{
    display: none;
}
.active{
     display: block !important;
}
    .panel {
        position: fixed;
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 100;
        max-height: 90vh;
        overflow-y: auto;
        transition: all 0.3s ease;
    }

    .controls-panel {
        left: 20px;
        top: 20px;
        width: 220px;
    }

    .building-form {
        right: 20px;
        top: 20px;
        width: 320px;
    }

    .panel-header {
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 15px;
        padding-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .panel-header h3 {
        margin: 0;
        color: var(--dark-color);
        font-size: 18px;
    }

    .tool-btn {
        margin: 5px 2px;
        padding: 8px 16px;
        cursor: pointer;
        border: 1px solid var(--border-color);
        background-color: white;
        border-radius: 4px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        font-weight: 500;
    }

    .tool-btn i {
        margin-left: 5px;
    }

    .tool-btn:hover {
        background-color: #f8f9fa;
    }

    .tool-btn.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .tool-btn.danger {
        color: var(--danger-color);
        border-color: var(--danger-color);
    }

    .tool-btn.danger:hover {
        background-color: var(--danger-color);
        color: white;
    }

    .tool-btn.danger.active {
        background-color: var(--danger-color);
        color: white;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: var(--dark-color);
    }

    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 2px rgba(52, 144, 220, 0.25);
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: #2779bd;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .btn-group {
        display: flex;
        gap: 10px;
    }

    .toggle-panel {
        position: fixed;
        background: var(--primary-color);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 101;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .toggle-controls {
        top: 20px;
        left: 20px;
    }

    .toggle-form {
        top: 20px;
        right: 20px;
    }

    .hidden {
        display: none;
    }

    .color-indicator {
        display: inline-block;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        margin-right: 5px;
        vertical-align: middle;
    }

    .status-sold {
        background-color: rgba(227, 52, 47, 0.7);
    }

    .status-available {
        background-color: rgba(56, 193, 114, 0.7);
    }

    .status-construction {
        background-color: rgba(255, 237, 74, 0.7);
    }

    .canvas-status {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.8);
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: white;
        padding: 15px 20px;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease;
    }

    .toast.show {
        opacity: 1;
        transform: translateY(0);
    }

    .toast-success {
        border-left: 4px solid var(--secondary-color);
    }

    .toast-error {
        border-left: 4px solid var(--danger-color);
    }

    /* مسؤول عن طريقة العرض في الشاشات الصغيرة */
    @media (max-width: 768px) {
        .controls-panel, .building-form {
            width: 100%;
            max-width: 100%;
            left: 0;
            right: 0;
            border-radius: 0;
            transform: translateY(-100%);
        }

        .controls-panel.active, .building-form.active {
            transform: translateY(0);
        }

        .toggle-panel {
            display: flex;
        }
    }
</style>
@endpush

@section('content')
<div class="building-layout-container">
    <div class="canvas-wrapper">
         <div class="map-container" id="mapContainer">
                    <canvas id="canvas"></canvas>
                </div>
        <div class="canvas-status">
            <span id="zoom-level">تكبير: 100%</span> | 
            <span id="coordinates">X: 0, Y: 0</span>
        </div>
    </div>

    <!-- زر تبديل لوحة التحكم -->
    <div class="toggle-panel toggle-controls" id="toggleControls">
        <i class="icon icon-settings"></i>
    </div>

    <!-- لوحة التحكم -->
    <div class="panel controls-panel " id="controlsPanel">
        <div class="panel-header">
            <h3>أدوات التحكم</h3>
            <button id="closeControls" class="btn btn-sm">×</button>
        </div>
        
        <div class="form-group">
            <div class="tool-btn" id="selectTool" title="تحديد وتحريك">
                <i class="icon icon-cursor"></i> تحديد
            </div>
            <div class="tool-btn" id="rectangleTool" title="رسم مستطيل">
                <i class="icon icon-square"></i> مستطيل
            </div>
            <div class="tool-btn danger" id="deleteTool" title="حذف عنصر">
                <i class="icon icon-trash"></i> حذف
            </div>
        </div>

        <div class="form-group">
            <div class="form-label">المقياس</div>
            <div style="display: flex; align-items: center;">
                <button class="btn btn-sm" id="zoomOut" title="تصغير">-</button>
                <input type="range" id="zoomSlider" min="20" max="500" value="100" style="flex: 1; margin: 0 10px;">
                <button class="btn btn-sm" id="zoomIn" title="تكبير">+</button>
            </div>
        </div>

        <div class="form-group">
            <div class="form-label">حالة المبنى</div>
            <select id="sold" class="form-control"> 
                <option value="غير مباعة">غير مباعة</option>
                <option value="مباعة">مباعة</option>
                <option value="تحت الانشاء">تحت الإنشاء</option>
            </select>
        </div>

        <div class="form-group">
            <div class="form-label">شفافية المباني</div>
            <input type="range" id="opacity" min="10" max="100" value="50" class="form-control">
        </div>

        <div class="form-group">
            <div class="form-label">دليل الألوان</div>
            <div>
                <span class="color-indicator status-available"></span> غير مباعة
            </div>
            <div>
                <span class="color-indicator status-sold"></span> مباعة
            </div>
            <div>
                <span class="color-indicator status-construction"></span> تحت الإنشاء
            </div>
        </div>

        <div class="form-group">
            <button id="resetView" class="btn btn-secondary" title="إعادة تعيين العرض">
                إعادة تعيين العرض
            </button>
        </div>
    </div>

    <!-- رسالة الإشعارات -->
    <div class="toast" id="toast"></div>
</div>
<div class="panel controls-panel" >
    <div class="panel-header">
            <h3>بيانات المبنى</h3>
    </div>

    <form id="buildingDataForm">
        <div class="row">
        <div class="form-group col-sm-4">
            <label class="form-label">رقم القطعة:</label>
            <input type="number" id="building_number" class="form-control" required /> 
        </div>
        
        <div class="form-group col-sm-4">
            <label class="form-label">رقم البلوك:</label>
            <input type="number" id="block_number" class="form-control"   />
        </div>
        
        <div class="form-group col-sm-4">
            <label class="form-label">عرض الشارع:</label>
            <input type="text" id="street_view" class="form-control" />
        </div>
        
        <div class="form-group col-sm-4">
            <label class="form-label">المساحة:</label>
            <input type="text" id="area" class="form-control"   />
        </div>
        
        <div class="form-group col-sm-4">
            <label class="form-label">الاتجاه:</label>
            <select id="direction" class="form-control"> 
                <option value=""></option>
                <option value="شرقي">شرقي</option>
                <option value="غربي">غربي</option>
                <option value="شمالي">شمالي</option>
                <option value="جنوبي">جنوبي</option>
                <option value="شرقي - جنوبي">شرقي - جنوبي</option>
                <option value="جنوبي - غربي">جنوبي - غربي</option>
                <option value="شرقي - شمالي">شرقي - شمالي</option>
                <option value="شمالي - غربي">شمالي - غربي</option>
                <option value="شرقي - جنوبي - شمالي">شرقي - جنوبي - شمالي</option>
            </select>
        </div>
        
        <div class="form-group col-sm-4">
            <label class="form-label">نوع المبنى:</label>
            <select id="type" class="form-control"  >
                <option value=""></option>
                <option value="تجاري">تجاري</option>
                <option value="سكني">سكني</option>
                <option value="مكتبي">مكتبي</option>
                <option value="فندقي">فندقي</option>
            </select>
        </div>
        
        <div class="form-group col-sm-4">
            <label class="form-label">السعر:</label>
            <input type="number" id="price" class="form-control">
        </div>
        
        <div class="btn-group col-sm-4">
            <button type="submit" class="btn btn-primary">حفظ البيانات</button>
            <button type="reset" class="btn btn-secondary">مسح البيانات</button>
        </div>
    </form>
</div>

@stop

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fabric@latest/dist/fabric.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        /**
         * التهيئة الأساسية
         */
        // تهيئة الكانفاس
        const canvas = new fabric.Canvas('canvas', { 
            selection: true,
            preserveObjectStacking: true,
            stopContextMenu: true
        });
           // تخزين البيانات
        const state = {
            currentTool: 'select',
            isDragging: false,
            isDrawing: false,
            currentShape: null,
            lastPosX: 0,
            lastPosY: 0,
            panMode: false,
            zoomLevel: 1,
            buildingData: @json($buildings),
            selectedBuilding: null
        };

        // تحديد الألوان حسب حالة المبنى
        const BUILDING_COLORS = {
            'مباعة': 'rgba(227, 52, 47, 0.7)',
            'غير مباعة': 'rgba(56, 193, 114, 0.7)',
            'تحت الانشاء': 'rgba(255, 237, 74, 0.7)'
        };

        /**
         * وظائف مساعدة
         */
        // إظهار رسالة للمستخدم
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `toast toast-${type} show`;
            
            setTimeout(() => {
                toast.className = 'toast';
            }, 3000);
        }

        // تحديث معلومات الكانفاس
        function updateCanvasInfo() {
            document.getElementById('zoom-level').textContent = `تكبير: ${Math.round(state.zoomLevel * 100)}%`;
        }

        // تحويل القيم إلى أرقام (للتأكد من صحة القيم الرقمية)
        function parseNumericValue(value) {
            return value === '' ? 0 : parseFloat(value);
        }

        // تعديل مقاس الكانفاس ليناسب الحاوية
    function resizeCanvas() {
        const container = document.getElementById('mapContainer');
        if (container) {
            canvas.setWidth(container.clientWidth);
            canvas.setHeight(600);
            canvas.renderAll();
        }
    }
resizeCanvas();
        // إضافة مبنى جديد إلى الكانفاس
        function addBuildingToCanvas(building) {
            const color = BUILDING_COLORS[building.sale] || BUILDING_COLORS['غير مباعة'];
            
            const rect = new fabric.Rect({
                left: building.coordinates.left,
                top: building.coordinates.top,
                width: building.coordinates.width,
                height: building.coordinates.height,
                angle: building.coordinates.angle || 0,
                fill: color,
                opacity: document.getElementById('opacity').value / 100,
                selectable: true,
                hoverCursor: 'pointer'
            });
            
            // إضافة بيانات المبنى كخاصية في الكائن
            rect.buildingData = building;
            
            // إضافة التسمية للمبنى
            const label = new fabric.Text(`${building.building_number}`, {
                fontSize: 10,
                fill: '#000000',
                originX: 'center',
                originY: 'center',
                left: rect.left + rect.width / 2,
                top: rect.top + rect.height / 2,
                selectable: false
            });
            
            // إنشاء مجموعة تضم المستطيل والتسمية
            const group = new fabric.Group([ rect, label ], {
                left: rect.left,
                top: rect.top,
                selectable: true,
                hoverCursor: 'pointer',
                hasControls: true,
                hasBorders: true,
                buildingData: building
            });
            
            // إضافة حدث النقر على المبنى
            group.on('selected', function() {
                if(state.currentTool === 'select') {
                    selectBuilding(this.buildingData);
                }
            });
            
            canvas.add(group);
            return group;
        }

        // اختيار مبنى وعرض بياناته في النموذج
        function selectBuilding(building) {
            state.selectedBuilding = building;
            
            // تعبئة النموذج بالبيانات
            document.getElementById('block_number').value = building.block_number || '';
            document.getElementById('sold').value = building.sale || 'غير مباعة';
            document.getElementById('building_number').value = building.building_number || '';
            document.getElementById('area').value = building.area || '';
            document.getElementById('street_view').value = building.street_view || '';
            document.getElementById('direction').value = building.direction || '';
            document.getElementById('type').value = building.type || '';
            document.getElementById('price').value = building.price || '';
            
            // عرض لوحة البيانات
            document.getElementById('buildingForm').classList.add('active');
        }

        // تحديث بيانات مبنى
        function updateBuildingData(buildingObj, newData) {
            // تحديث البيانات في كائن المبنى
            Object.assign(buildingObj.buildingData, newData);
            
            // تحديث لون المبنى حسب الحالة
            const color = BUILDING_COLORS[newData.sale] || BUILDING_COLORS['غير مباعة'];
            
            // تحديث الكائن المرئي
            const rect = buildingObj.getObjects('rect')[0];
            if (rect) {
                rect.set({
                    fill: color,
                    opacity: document.getElementById('opacity').value / 100
                });
            }
            
            // تحديث التسمية
            const label = buildingObj.getObjects('text')[0];
            if (label) {
                label.set({
                    text: newData.building_number.toString()
                });
            }
            
            canvas.renderAll();
        }

        // حذف مبنى من الكانفاس
        function deleteBuilding(buildingObj) {
            canvas.remove(buildingObj);
            canvas.renderAll();
            
            // إذا كان المبنى المحذوف هو المحدد حالياً، قم بإعادة تعيين حالة التحديد
            if (state.selectedBuilding && buildingObj.buildingData.id === state.selectedBuilding.id) {
                state.selectedBuilding = null;
                document.getElementById('buildingDataForm').reset();
            }
        }

        // تعيين أداة التحكم الحالية
        function setTool(tool) {
            state.currentTool = tool;
            
            // إزالة التصنيف النشط من جميع الأزرار
            document.querySelectorAll('.tool-btn').forEach(btn => btn.classList.remove('active'));
            
            // إضافة التصنيف النشط للزر المحدد
            document.getElementById(`${tool}Tool`).classList.add('active');
            
            // إعدادات الكانفاس بناءً على الأداة المحددة
            canvas.isDrawingMode = false;
            canvas.selection = (tool === 'select');
            
            // إذا كانت أداة الحذف، نفعل وضع الحذف
            if (tool === 'delete' && canvas.getActiveObject()) {
                deleteBuilding(canvas.getActiveObject());
                setTool('select'); // العودة إلى أداة التحديد بعد الحذف
            }
        }

        /**
         * تحميل صورة المخطط
         */
        fabric.Image.fromURL('{{$buildingPlan->pathImage->url}}', function(img) {
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

        /**
         * إضافة المباني الموجودة إلى الكانفاس
         */
        if (state.buildingData && state.buildingData.length) {
            state.buildingData.forEach(building => {
                addBuildingToCanvas(building);
            });
        }

        /**
         * أحداث أزرار التحكم
         */
        // أزرار أدوات التحكم
        document.getElementById('selectTool').addEventListener('click', () => setTool('select'));
        document.getElementById('rectangleTool').addEventListener('click', () => setTool('rectangle'));
        document.getElementById('deleteTool').addEventListener('click', () => setTool('delete'));
        
        // أزرار التكبير والتصغير
        document.getElementById('zoomIn').addEventListener('click', function() {
            let zoom = state.zoomLevel * 1.1;
            zoom = Math.min(zoom, 5);
            const center = { x: canvas.width / 2, y: canvas.height / 2 };
            canvas.zoomToPoint(center, zoom);
            state.zoomLevel = zoom;
            updateCanvasInfo();
        });
        
        document.getElementById('zoomOut').addEventListener('click', function() {
            let zoom = state.zoomLevel / 1.1;
            zoom = Math.max(zoom, 0.2);
            const center = { x: canvas.width / 2, y: canvas.height / 2 };
            canvas.zoomToPoint(center, zoom);
            state.zoomLevel = zoom;
            updateCanvasInfo();
        });
        
        // شريط تمرير التكبير
        document.getElementById('zoomSlider').addEventListener('input', function() {
            const zoom = parseInt(this.value) / 100;
            const center = { x: canvas.width / 2, y: canvas.height / 2 };
            canvas.zoomToPoint(center, zoom);
            state.zoomLevel = zoom;
            updateCanvasInfo();
        });
        
        // زر إعادة تعيين العرض
        document.getElementById('resetView').addEventListener('click', function() {
            canvas.setViewportTransform([1, 0, 0, 1, 0, 0]);
            state.zoomLevel = 1;
            document.getElementById('zoomSlider').value = 100;
            updateCanvasInfo();
        });
        
        // تغيير شفافية المباني
        document.getElementById('opacity').addEventListener('input', function() {
            const opacity = this.value / 100;
            canvas.forEachObject(function(obj) {
                if (obj.type === 'group') {
                    const rect = obj.getObjects('rect')[0];
                    if (rect) {
                        rect.set('opacity', opacity);
                    }
                }
            });
            canvas.renderAll();
        });

        /**
         * أحداث تبديل اللوحات
         */
        document.getElementById('toggleControls').addEventListener('click', function() {
            document.getElementById('controlsPanel').classList.toggle('active');
        });
        
        document.getElementById('closeControls').addEventListener('click', function() {
            document.getElementById('controlsPanel').classList.remove('active');
        });
        
        document.getElementById('toggleForm').addEventListener('click', function() {
            document.getElementById('buildingForm').classList.toggle('active');
        });
        
        document.getElementById('closeForm').addEventListener('click', function() {
            document.getElementById('buildingForm').classList.remove('active');
        });

        /**
         * أحداث الكانفاس
         */
        // تعديل حجم الكانفاس عند تغيير حجم النافذة
        window.addEventListener('resize', resizeCanvas);
        
        // التكبير والتصغير باستخدام عجلة الفأرة
        canvas.on('mouse:wheel', function(e) {
            e.e.preventDefault();
            
            let zoom = canvas.getZoom();
            zoom = e.e.deltaY < 0 ? zoom * 1.1 : zoom / 1.1;
            zoom = Math.min(Math.max(0.2, zoom), 5);
            
            canvas.zoomToPoint({ x: e.e.offsetX, y: e.e.offsetY }, zoom);
            state.zoomLevel = zoom;
            document.getElementById('zoomSlider').value = Math.round(zoom * 100);
            updateCanvasInfo();
        });
        
        // تحديث إحداثيات المؤشر
        canvas.on('mouse:move', function(e) {
            const pointer = canvas.getPointer(e.e);
            document.getElementById('coordinates').textContent = `X: ${Math.round(pointer.x)}, Y: ${Math.round(pointer.y)}`;
            
            // تحريك الكانفاس عند السحب في وضع التحديد
            if (state.isDragging && state.currentTool === 'select' && !state.isDrawing) {
                const deltaX = e.e.clientX - state.lastPosX;
                const deltaY = e.e.clientY - state.lastPosY;
                
                canvas.relativePan(new fabric.Point(deltaX, deltaY));
                
                state.lastPosX = e.e.clientX;
                state.lastPosY = e.e.clientY;
            }
            
            // تحديث المستطيل أثناء الرسم
            if (state.isDrawing && state.currentTool === 'rectangle') {
                const pointer = canvas.getPointer(e.e);
                
                if (state.currentShape) {
                    if (pointer.x < state.currentShape.left) {
                        state.currentShape.set({ left: pointer.x });
                    }
                    if (pointer.y < state.currentShape.top) {
                        state.currentShape.set({ top: pointer.y });
                    }
                    
                    state.currentShape.set({
                        width: Math.abs(pointer.x - state.currentShape.left),
                        height: Math.abs(pointer.y - state.currentShape.top)
                    });
                    
                    canvas.renderAll();
                }
            }});
        // بدء السحب عند الضغط بزر الفأرة
        canvas.on('mouse:down', function(e) {
            const pointer = canvas.getPointer(e.e);
            
            // وضع السحب (للتحريك)
            if (state.currentTool === 'select' && e.e.button === 0) {
                state.isDragging = true;
                state.lastPosX = e.e.clientX;
                state.lastPosY = e.e.clientY;
            }
            
            // وضع الرسم (للمستطيلات)
            if (state.currentTool === 'rectangle' && !state.isDrawing) {
                state.isDrawing = true;
                
                // تحديد لون المبنى حسب الحالة
                const status = document.getElementById('sold').value;
                const color = BUILDING_COLORS[status] || BUILDING_COLORS['غير مباعة'];
                const opacity = document.getElementById('opacity').value / 100;
                
                // إنشاء مستطيل جديد
                state.currentShape = new fabric.Rect({
                    left: pointer.x,
                    top: pointer.y,
                    width: 0,
                    height: 0,
                    fill: color,
                    opacity: opacity,
                    selectable: true
                });
                
                canvas.add(state.currentShape);
            }
        });
        
        // إنهاء السحب أو الرسم عند رفع زر الفأرة
        canvas.on('mouse:up', function() {
            // إنهاء وضع السحب
            state.isDragging = false;
            
            // إنهاء وضع الرسم وإضافة المبنى إذا تم رسم مستطيل بحجم كافٍ
            if (state.isDrawing && state.currentTool === 'rectangle') {
                state.isDrawing = false;
                
                if (state.currentShape && state.currentShape.width > 5 && state.currentShape.height > 5) {
                    // تهيئة النموذج لإدخال بيانات المبنى الجديد
                    document.getElementById('buildingDataForm').reset();
                    document.getElementById('buildingForm').classList.add('active');
                    
                    // تعيين المستطيل كالكائن النشط
                    canvas.setActiveObject(state.currentShape);
                    state.currentShape.setCoords();
                } else if (state.currentShape) {
                    // إزالة المستطيل إذا كان صغيرًا جدًا
                    canvas.remove(state.currentShape);
                }
                
                state.currentShape = null;
            }
        });
        
        // تحديد كائن عند النقر عليه
        canvas.on('selection:created', function(e) {
            if (state.currentTool === 'select') {
                const obj = e.selected[0];
                if (obj && obj.buildingData) {
                    selectBuilding(obj.buildingData);
                }
            }
        });
        
        // إزالة التحديد عند النقر خارج الكائنات
        canvas.on('selection:cleared', function() {
            if (state.currentTool === 'select') {
                state.selectedBuilding = null;
            }
        });

        /**
         * أحداث نموذج البيانات
         */
        // حفظ بيانات المبنى
        document.getElementById('buildingDataForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // الحصول على الكائن النشط
            const activeObject = canvas.getActiveObject();
            if (!activeObject) {
                showToast('الرجاء تحديد شكل أولاً', 'error');
                return;
            }
            
            // تجميع بيانات المبنى من النموذج
            const buildingData = {
                block_number: document.getElementById('block_number').value,
                sale: document.getElementById('sold').value,
                building_number: document.getElementById('building_number').value,
                area: document.getElementById('area').value,
                street_view: document.getElementById('street_view').value,
                direction: document.getElementById('direction').value,
                type: document.getElementById('type').value,
                price: document.getElementById('price').value,
                building_plan_id: {{$buildingPlan->id}},
                 coordinates: {
                }
            };
            
            // إذا كان الكائن مستطيلًا (مبنى جديد)
            if (activeObject.type === 'rect') {
                buildingData.coordinates = {
                    left: activeObject.left,
                    top: activeObject.top,
                    width: activeObject.width * activeObject.scaleX,
                    height: activeObject.height * activeObject.scaleY,
                    angle: activeObject.angle || 0
                };
                
                // إرسال البيانات للخادم لإضافة مبنى جديد
                window.axios.post('{{route("building.store",$buildingPlan->id)}}', buildingData)
                    .then(response => {
                        // إضافة هوية المبنى المسترجعة من الخادم
                        buildingData.id = response.data.id || state.buildingData.length + 1;
                        
                        // إضافة المبنى إلى القائمة المحلية
                        state.buildingData.push(buildingData);
                        
                        // إزالة المستطيل المؤقت
                        canvas.remove(activeObject);
                        
                        // إضافة مبنى جديد مع التسمية
                        const newBuilding = addBuildingToCanvas(buildingData);
                        canvas.setActiveObject(newBuilding);
                        
                        showToast('تم إضافة المبنى بنجاح');
                    })
                    .catch(error => {
                        showToast('حدث خطأ أثناء إضافة المبنى', 'error');
                        console.error(error);
                    });
            } 
            // إذا كان الكائن مجموعة (مبنى موجود)
            else if (activeObject.type === 'group' && activeObject.buildingData) {
                const building = activeObject.buildingData;
                
                // تحديث الإحداثيات
                buildingData.coordinates = {
                    left: activeObject.left,
                    top: activeObject.top,
                    width: activeObject.width,
                    height: activeObject.height,
                    angle: activeObject.angle || 0
                };
                
                // إضافة هوية المبنى
                buildingData.id = building.id;
                
                // إرسال البيانات للخادم لتحديث المبنى
                window.axios.post('{{route("building.store",$buildingPlan->id)}}', buildingData)
                    .then(response => {
                        // تحديث البيانات والمظهر
                        updateBuildingData(activeObject, buildingData);
                        
                        // تحديث المبنى في القائمة المحلية
                        const index = state.buildingData.findIndex(b => b.id === building.id);
                        if (index !== -1) {
                            state.buildingData[index] = { ...state.buildingData[index], ...buildingData };
                        }
                        
                        showToast('تم تحديث بيانات المبنى بنجاح');
                    })
                    .catch(error => {
                        showToast('حدث خطأ أثناء تحديث بيانات المبنى', 'error');
                        console.error(error);
                    });
            }
        });
        
        // إعادة تعيين النموذج
        document.getElementById('buildingDataForm').addEventListener('reset', function() {
            state.selectedBuilding = null;
        });

        // التهيئة الأولية
        resizeCanvas();
        setTool('select');
        updateCanvasInfo();
    });
</script>
@endpush