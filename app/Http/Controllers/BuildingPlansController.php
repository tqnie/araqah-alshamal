<?php

namespace App\Http\Controllers;

use App\Imports\BuildingsImport;
use App\Models\Building;
use App\Models\BuildingPlan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BuildingPlansController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $id)
    {
        $buildingPlan = BuildingPlan::find($id);
        $buildings = [];
        foreach ($buildingPlan->buildings as $item) {
            $buildings[] =  [
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
                    'angle' => $item->angle
                ]
            ];
        };
        return view('building_plans.index', [
            'buildingPlan' => $buildingPlan,
            'buildings' => $buildings,
        ]);
    }

    public function buildingStore(Request $request, $id)
    {
        $request->validate([
            'building_plan_id' => 'required|int',
            'sale' => 'required|string',
        ]);
        if ($building = Building::where('building_plan_id', $request->building_plan_id)->where('building_number', $request->building_number)->first()) {
            $data = [
                'sale' => $request->sale,
                'block_number' => $request->block_number,
                'price' => $request->price,
                'area' => $request->area,
                'street_view' => $request->street_view,
                'direction' => $request->direction,
                'type' => $request->type,
                'x' => $request->coordinates['left'],
                'y' => $request->coordinates['top'],
                'width' => $request->coordinates['width'],
                'height' => $request->coordinates['height'],
                'active' => '1'
            ]; 
             foreach ($data as $key => $value) {
                if ($value == null || $value == '') {
                    unset($data[$key]);
                }
            }

            $building->update($data);
        } else {

            Building::create([
                'building_plan_id' => $request->building_plan_id,
                'building_number' => $request->building_number,
                'sale' => $request->sale,
                'block_number' => $request->block_number,
                'price' => $request->price,
                'area' => $request->area,
                'street_view' => $request->street_view,
                'direction' => $request->direction,
                'type' => $request->type,
                'x' => $request->coordinates['left'],
                'y' => $request->coordinates['top'],
                'width' => $request->coordinates['width'],
                'height' => $request->coordinates['height'],
                'active' => '1'
            ]);
        }
        return 'success building add';
    }
    /**
     * @return \Illuminate\View\View
     */
    public function uploadExcel(Request $request, $id)
    {
        $buildingPlan = BuildingPlan::find($id);

        return view('building_plans.upload-excel', [
            'buildingPlan' => $buildingPlan,
        ]);
    }
    /**
     * @return \Illuminate\View\View
     */
    public function uploadExcelAction(Request $request, $id)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $buildingPlan = BuildingPlan::findOrFail($id);
        $file = $request->file('excel_file');

        Excel::import(new BuildingsImport($buildingPlan->id), $file);

        return redirect()->route('building.uploadExcel', $buildingPlan->id)
            ->with('success', 'تم استيراد البيانات بنجاح.');
    }
}
