<?php 

namespace App\Laravel\Models\Imports;

use App\Laravel\Models\ZoneLocation;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

use Str, Helper, Carbon;

class ZoneLocationImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // dd($rows);

        foreach ($rows as $index => $row) 
        {
            if($index == 0) {
                continue;
            }

            $is_exist = ZoneLocation::where('code',$row[0])->first();

            if (!$is_exist) {
                 $zone_location = ZoneLocation::create([
                    'code' => $row[0],
                    'ecozone' => $row[1],
                    'type' => str_replace(" ", "_", strtolower($row[2])),
                    'nature' => $row[3],
                    'address' => $row[4],
                    'developer' => $row[5],
                    'city' => $row[6],
                    'province' => $row[7],
                    'region' => $row[8],
                    'dev_comp_code' => $row[9],
                    'obo_cluster' => $row[10],
                    'income_cluster' => $row[11],
                    'serial' => $row[12],
                    'region_code' => $row[13],

                ]);
                $zone_location->save();
            }
           
           
        }
    }
}