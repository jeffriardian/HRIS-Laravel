<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\Leave as Model;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();
        $data = [
            ['id'=>1,'leave_category_id'=>'1','employee_id'=>'2','total'=>'3','start_date'=>'2020-04-06','end_date'=>'2020-04-08','is_active'=>'1','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'leave_category_id'=>'2','employee_id'=>'1','total'=>'3','start_date'=>'2020-04-06','end_date'=>'2020-04-08','is_active'=>'1','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'leave_category_id'=>'4','employee_id'=>'5','total'=>'2','start_date'=>'2020-04-06','end_date'=>'2020-04-07','is_active'=>'1','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'leave_category_id'=>'2','employee_id'=>'5','total'=>'2','start_date'=>'2020-04-08','end_date'=>'2020-04-09','is_active'=>'1','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];

        Model::insert($data);
    }
}
