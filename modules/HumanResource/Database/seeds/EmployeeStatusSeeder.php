<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\EmployeeStatus as Model;

class EmployeeStatusSeeder extends Seeder
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
            ['id'=>999,'contract_id'=>'0','period_id'=>'0','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>1,'contract_id'=>'2','period_id'=>'1','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'contract_id'=>'1','period_id'=>'3','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'contract_id'=>'1','period_id'=>'4','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'contract_id'=>'1','period_id'=>'5','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>5,'contract_id'=>'1','period_id'=>'6','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>6,'contract_id'=>'1','period_id'=>'8','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];

        Model::insert($data);
    }
}
