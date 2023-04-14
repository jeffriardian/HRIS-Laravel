<?php

namespace Modules\Engineering\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\Engineering\Models\FailureType as Model;

class FailureTypeSeeder extends Seeder
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
            ['code'=>'CR','name' => 'Crack','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'LS','name' => 'Loose','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'AU','name' => 'Abnormal Use','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'AN','name' => 'Abnormal Noise','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];
        
        Model::insert($data);
    }
}
