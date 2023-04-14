<?php

namespace Modules\Engineering\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\Engineering\Models\ServiceType as Model;

class ServiceTypeSeeder extends Seeder
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
            ['code'=>'20H','name' => 'Daily Check (20 Jam)','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'250H','name' => 'Service I (250 Jam)','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'500H','name' => 'Service II (500 Jam)','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'1000H','name' => 'Service III (1000 Jam)','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'2000H','name' => 'Service IV (2000 Jam)','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];
        
        Model::insert($data);
    }
}
