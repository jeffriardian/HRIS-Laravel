<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\CooperativeBasicSaving as Model;

class CooperativeBasicSavingSeeder extends Seeder
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
            ['id'=>1,'cooperative_member_type_id'=>'1','amount'=>'250000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'cooperative_member_type_id'=>'2','amount'=>'40000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];

        Model::insert($data);
    }
}
