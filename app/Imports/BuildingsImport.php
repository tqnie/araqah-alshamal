<?php

namespace App\Imports;

use App\Models\Building;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BuildingsImport implements ToModel, WithHeadingRow
{

    protected $externalId;

    public function __construct($externalId = null)
    {
        $this->externalId = $externalId;
    }

    public function model(array $row)
    {
        $buildingNumber =   $this->externalId;

        if ($buildingNumber != null && $row['id'] != null && $row['block'] != null) {
            if ($building = Building::where('building_plan_id', $buildingNumber)->where('block_number',$row['block'])->where('building_number', $row['id'])->first()) {
                $building->update([  
                    'price' => $row['price'] ?? null,
                    'area' => $row['area'] ?? null,
                    'sale' => $row['sale'] ?? null,
                    'street_view' => $row['street_view'] ?? null,
                    'direction' => $row['direction'] ?? null,
                    'type' => $row['direction'] ?? null
                ]);
            } else {
                return new Building([
                    'building_plan_id' => $buildingNumber,
                    'building_number' => $row['id'],
                    'block_number' => $row['block'] ?? null,
                    'price' => $row['price'] ?? null,
                    'area' => $row['area'] ?? null,
                    'street_view' => $row['street_view'] ?? null,
                    'direction' => $row['direction'] ?? null,
                    'type' => $row['direction'] ?? null
                ]);
            }
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
