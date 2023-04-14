<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\Ptkp as Model;

class PtkpSeeder extends Seeder
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
            ['code'=>'K/0','amount_ptkp'=>'58500000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'K/1','amount_ptkp'=>'63000000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'K/2','amount_ptkp'=>'67500000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'K/3','amount_ptkp'=>'72000000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'K/I/0','amount_ptkp'=>'112500000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'K/I/1','amount_ptkp'=>'117500000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'K/I/2','amount_ptkp'=>'121500000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'K/I/3','amount_ptkp'=>'126000000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'TK/0','amount_ptkp'=>'54000000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'TK/1','amount_ptkp'=>'58500000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'TK/2','amount_ptkp'=>'63000000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'TK/3','amount_ptkp'=>'67500000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];

        Model::insert($data);
    }
}
