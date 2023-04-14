<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\Insurance as Model;

class InsuranceSeeder extends Seeder
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
            ['id'=>1,'bpjstk'=>'86962','bpjskes'=>'31454','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];

        Model::insert($data);
    }
}
