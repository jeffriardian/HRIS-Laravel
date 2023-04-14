<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\HumanResource\Models\OfficeHourDetail;

class OfficeHourDetailTableSeeder extends Seeder
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
            ['id' => 1, 'office_hour_id' => 1, 'weekday_in' => '07:00:00', 'weekday_out' => '15:00:00', 'weekend_in' => '07:00:00', 'weekend_out' => '15:00:00', 'is_active' => 1, 'created_by' => 1, 'updated_by' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'office_hour_id' => 2, 'weekday_in' => '15:00:00', 'weekday_out' => '23:00:00', 'weekend_in' => '15:00:00', 'weekend_out' => '23:00:00', 'is_active' => 1, 'created_by' => 1, 'updated_by' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'office_hour_id' => 3, 'weekday_in' => '23:00:00', 'weekday_out' => '07:00:00', 'weekend_in' => '23:00:00', 'weekend_out' => '07:00:00', 'is_active' => 1, 'created_by' => 1, 'updated_by' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];

        OfficeHourDetail::insert($data);
    }
}
