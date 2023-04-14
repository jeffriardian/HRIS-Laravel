<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\Contract as Model;

class ContractSeeder extends Seeder
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
            ['id'=>1,'name'=>'Kontrak','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'name'=>'Tetap','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now]
        ];

        Model::insert($data);
    }
}
