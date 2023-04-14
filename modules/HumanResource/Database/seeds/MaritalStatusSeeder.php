<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\MaritalStatus as Model;

class MaritalStatusSeeder extends Seeder
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
            ['id'=>999,'name'=>'Undefined','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>1,'name'=>'Belum Menikah','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'name'=>'Menikah','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now]
        ];

        Model::insert($data);
    }
}
