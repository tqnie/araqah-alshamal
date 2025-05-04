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
        $buildings = [];
        foreach($buildingPlan->buildings as $item){
            $buildings[]=  [
                'id'=> $item->id,
                'name'=> $item->building_bloc,
                'sale'=> $item->building_sale,
                'building_number'=> $item->building_number,
                'area'=> $item->building_area,
                'street_view'=> $item->street_view,
                'direction'=> $item->building_direction,
                'type'=> $item->building_type,
                'price'=> $item->building_price,
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
        return view('building_plans.index', [
            'buildingPlan' => $buildingPlan,
            'buildings'=>$buildings,
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
