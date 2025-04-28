<?php

namespace App\Http\Controllers;

use App\Models\BuildingPlan;
use Illuminate\Http\Request;

class BuildingPlansController extends Controller
{
    
    /**
     * @return \Illuminate\View\View
     */
    public function index(Request $request ,$id)
    {
        $buildingPlan = BuildingPlan::find($id);
        return view('building_plans.index',[
            'buildingPlan' => $buildingPlan,
        ]);
    } 
}
