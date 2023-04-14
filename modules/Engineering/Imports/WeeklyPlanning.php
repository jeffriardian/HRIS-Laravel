<?php

namespace Modules\Engineering\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\Production\Models\MachineGroup;
use Modules\Engineering\Models\EngineeringWeeklyPlanning as Model;
use Modules\Engineering\Models\EngineeringWeeklyPlanningDetail as ModelDetail;

class WeeklyPlanning implements ToCollection
{
    public $month;
    public $week;

    public function  __construct($month, $week)
    {
        $this->month = $month;
        $this->week = $week;
    }

    /**
    * @param Collection $rows
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $modelDetail = [];
        foreach($rows as $index => $row){
            if ($index < 1) continue;

            $modelDetail[] = [
                'machine_group_id' => $this->getMachineGroup($row[1]),
                '1' => $row[3],
                '2' => $row[4],
                '3' => $row[5],
                '4' => $row[6],
                '5' => $row[7],
                '6' => $row[8],
                '7' => $row[9],
            ];
        }
        
        $model = new Model;
        $model->engineering_monthly_planning_id = $this->month;
        $model->week = $this->week;
        $model->save();

        $model->details()->createMany($modelDetail);
    }

    public function getMachineGroup($code){
        $model = MachineGroup::where('code', $code)->first();
        return $model ? $model->id : 0;
    }
}
