<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\DepartmentLevel as Model;

class DepartmentLevelSeeder extends Seeder
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
            ['id'=>999,'code'=>'UD','name'=>'Undefined','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>1,'code'=>'BOD','name'=>'Board of Directors','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'code'=>'CHF','name'=>'Chief','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'code'=>'MNG','name'=>'Manager','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'code'=>'DPH','name'=>'Departemen Head','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>5,'code'=>'ICN','name'=>'Individual Contributors','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>6,'code'=>'ADM','name'=>'Admin/Foreman','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>7,'code'=>'OPR','name'=>'Operator','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now]
        ];

        Model::insert($data);
    }
}
