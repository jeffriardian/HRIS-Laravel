<?php

namespace Modules\Engineering\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\Engineering\Models\WorkOrderAction as Model;

class WorkOrderActionSeeder extends Seeder
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
            ['code'=>'D','name' => 'Daily Check','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'P','name' => 'Periodical Service','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'S','name' => 'Scheduled Repair','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'N','name' => 'New Project','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];
        
        Model::insert($data);
    }
}
