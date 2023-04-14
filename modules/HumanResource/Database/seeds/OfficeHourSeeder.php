<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\OfficeHour as Model;

class OfficeHourSeeder extends Seeder
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
            // ['id'=>999,'name'=>'Undefined', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>1,'name'=>'NS 07.00-16.30 & 07.00-16.00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>2,'name'=>'NS 07.00-17.00 & 07.00-17.00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>3,'name'=>'NS 08.00-16.00 & 08.00-16.00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>4,'name'=>'NS 07.00-15.00 & 07.00-15.00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>5,'name'=>'NS 09.00-17.00 & 09.00-17.00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>6,'name'=>'NS 08.00-18.00 & 08.00-18.00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>7,'name'=>'NS 07.00-17.00 & 07.00-16.00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>8,'name'=>'NS 07.00-17.00 & 07.00-12.00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>9,'name'=>'NS 07.30-17.00 & 07.30-17.00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>10,'name'=>'NS 07.00-16.30 & 07.00-13.00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>11,'name'=>'NS 08.00-17.00 & 08.00-17.00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>12,'name'=>'NS 07.00-16.00 & 07.00-16.00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>13,'name'=>'NS 06.00-14.00 & 06.00-14.00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            // ['id'=>14,'name'=>'Shift 1', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>15,'name'=>'Shift 2', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>16,'name'=>'Shift 3', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>17,'name'=>'Shift 4', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>18,'name'=>'Shift 5', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>19,'name'=>'Shift 6', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>20,'name'=>'Shift 7', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>21,'name'=>'Shift 8', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            // ['id'=>22,'name'=>'Shift 9', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id' => 1, 'name' => 'Shift 01', 'weekday_in' => '07:00:00', 'weekday_out' => '15:00:00', 'weekend_in' => '07:00:00', 'weekend_out' => '15:00:00', 'is_active' => 1, 'created_by' => 1, 'updated_by' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Shift 02', 'weekday_in' => '15:00:00', 'weekday_out' => '23:00:00', 'weekend_in' => '15:00:00', 'weekend_out' => '23:00:00', 'is_active' => 1, 'created_by' => 1, 'updated_by' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Shift 03', 'weekday_in' => '23:00:00', 'weekday_out' => '07:00:00', 'weekend_in' => '23:00:00', 'weekend_out' => '07:00:00', 'is_active' => 1, 'created_by' => 1, 'updated_by' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];

        Model::insert($data);
    }
}
