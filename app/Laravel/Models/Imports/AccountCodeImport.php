<?php 

namespace App\Laravel\Models\Imports;

use App\Laravel\Models\AccountCode;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

use Str, Helper, Carbon;

class AccountCodeImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // dd($rows);

        foreach ($rows as $index => $row) 
        {
            if($index == 0) {
                continue;
            }

            $is_exist = AccountCode::where('code',$row[0])->first();

            if (!$is_exist) {
                 $department = AccountCode::create([
                'code' => $row[0],
                'description' => $row[1],
                'alias' => $row[2],
                'default_cost' => $row[3],
                'ngas_code' => $row[4],
                'assigned_to_unit' => $row[5],
                ]);
               
                $department->save();
            }
           
           
        }
    }
}