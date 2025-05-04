<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\BuildingPlan;
use Illuminate\Http\Request;

class BuildingPlansController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $id)
    {
        $buildingPlan = BuildingPlan::find($id);
        return view('building_plans.index', [
            'buildingPlan' => $buildingPlan,
        ]);
    }

    public function buildingStore(Request $request, $id) {
        $request->validate([
            'building_plan_id' => 'required|int',
            'price' => 'required|string',
            'sale' => 'required|string',
        ]);
        Building::create([
            'building_plan_id'=>$request->building_plan_id,
            'building_number'=>$request->building_number,
            'sale'=>$request->sale,
            'price'=>$request->price,
            'area'=>$request->area,
            'street_view'=>$request->street_view,
            'direction'=>$request->direction,
            'type'=>$request->type,
            'x'=>$request->coordinates['left'],
            'y'=>$request->coordinates['top'],
            'width'=>$request->coordinates['width'],
            'height'=>$request->coordinates['height'],
            'active'=>1
        ]);
        return 'success building add';
    }
}
