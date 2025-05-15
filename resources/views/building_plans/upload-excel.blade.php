@extends('platform::dashboard')

@section('title', 'تخطيط المباني')
@section('description', 'تخطيط المباني')

 
{{-- street_view<direction<type<area<price<sale<building_number<building_plan_id --}}
@section('content')
<div class="text-center mt-5 mb-5">
   

    <div class="building-form">
        <h3>إضافة  جديد</h3>
       
        <form action="{{ route('building.uploadExcel.upload',$buildingPlan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="excel_file">رفع ملف Excel:</label>
                <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls" required>
            </div>
            <button type="submit" class="btn btn-primary">رفع الملف</button>
        </form>
        @if(session('success'))
            <div class="alert alert-success mt-2">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mt-2">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="buildings-list">
            <h3>المباني المضافة</h3>
          <h2>{{$buildingPlan->buildings->count()}}</h2>
        </div>
    </div>
</div>
@stop

 
 
