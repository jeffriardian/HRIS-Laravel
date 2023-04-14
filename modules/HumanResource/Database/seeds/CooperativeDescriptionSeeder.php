<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\CooperativeDescription as Model;

class CooperativeDescriptionSeeder extends Seeder
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
            ['id'=>1,'cooperative_category_id'=>'1','cooperative_type_id'=>'1','name'=>'Mutation','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'cooperative_category_id'=>'1','cooperative_type_id'=>'1','name'=>'Fixed Saving','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'cooperative_category_id'=>'1','cooperative_type_id'=>'1','name'=>'UnFixed Saving','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'cooperative_category_id'=>'2','cooperative_type_id'=>'1','name'=>'Withdrawal','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>5,'cooperative_category_id'=>'1','cooperative_type_id'=>'2','name'=>'Loan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>6,'cooperative_category_id'=>'2','cooperative_type_id'=>'2','name'=>'Installment Payment','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];

        Model::insert($data);
    }
}
