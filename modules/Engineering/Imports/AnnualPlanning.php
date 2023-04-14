<?php

namespace Modules\Engineering\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\Production\Models\Machine;
use Modules\Engineering\Models\TypeService;
use Modules\Engineering\Models\EngineeringAnnualPlanning as Model;
use Modules\Engineering\Models\EngineeringAnnualPlanningDetail as ModelDetail;

class AnnualPlanning implements ToCollection
{
    public $year;

    public function  __construct($year)
    {
        $this->year = $year;
    }

    /**
    * @param Collection $rows
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $monthList = $this->mergeMonth($rows[0]->slice(5));

        $modelDetail = [];
        foreach($rows as $index => $row){
            if ($index < 3) continue;

            $modelDetail[] = [
                'machine_id' => $this->getMachine($row[4]),
                '1_1' => $row[5],
                '1_2' => $row[6],
                '1_3' => $row[7],
                '1_4' => $row[8],
                '2_1' => $row[9],
                '2_2' => $row[10],
                '2_3' => $row[11],
                '2_4' => $row[12],
                '3_1' => $row[13],
                '3_2' => $row[14],
                '3_3' => $row[15],
                '3_4' => $row[16],
                '4_1' => $row[17],
                '4_2' => $row[18],
                '4_3' => $row[19],
                '4_4' => $row[20],
                '5_1' => $row[21],
                '5_2' => $row[22],
                '5_3' => $row[23],
                '5_4' => $row[24],
                '6_1' => $row[25],
                '6_2' => $row[26],
                '6_3' => $row[27],
                '6_4' => $row[28],
                '7_1' => $row[29],
                '7_2' => $row[30],
                '7_3' => $row[31],
                '7_4' => $row[32],
                '8_1' => $row[33],
                '8_2' => $row[34],
                '8_3' => $row[35],
                '8_4' => $row[36],
                '9_1' => $row[37],
                '9_2' => $row[38],
                '9_3' => $row[39],
                '9_4' => $row[40],
                '10_1' => $row[41],
                '10_2' => $row[42],
                '10_3' => $row[43],
                '10_4' => $row[44],
                '11_1' => $row[45],
                '11_2' => $row[46],
                '11_3' => $row[47],
                '11_4' => $row[48],
                '12_1' => $row[49],
                '12_2' => $row[50],
                '12_3' => $row[51],
                '12_4' => $row[52],
            ];
        }
        
        $model = new Model;
        $model->year = $this->year;
        $model->save();

        $model->details()->createMany($modelDetail);
    }

    // public function collection(Collection $rows)
    // {
    //     $monthList = $this->mergeMonth($rows[0]->slice(5));

    //     $modelDetail = [];
    //     foreach($rows as $index => $row){
    //         if ($index < 3) continue;

    //         $columnIndex = 1;
    //         foreach($monthList as $month){
    //             for($week=1;$week<=4;$week++){
    //                 $modelDetail[] = [
    //                     'week' => $week,
    //                     'month' => $month,
    //                     'machine_id' => $this->getMachine($row[4]),
    //                     'type_service_id' => $this->getTypeService($row[4+$columnIndex])
    //                 ];
    //                 $columnIndex++;
    //             }
    //         }
    //     }
        
    //     $model = new Model;
    //     $model->year = $this->year;
    //     $model->save();

    //     $model->details()->createMany($modelDetail);
    // }

    public function getMachine($code){
        $model = Machine::where('code', $code)->first();
        return $model ? $model->id : 0;
    }

    public function getTypeService($code){
        $model = TypeService::where('code', $code)->first();
        return $model ? $model->id : 0;
    }

    public function mergeMonth($collection){
        $filtered = $collection->reject(function ($value, $key) {
            return $value == NULL;
        });

        return array_values($filtered->all());
    }
}
