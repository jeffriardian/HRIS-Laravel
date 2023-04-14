<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\HumanResource\Models\DayOff;

class DayOffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'Saturday'],
            ['id' => 2, 'name' => 'Sunday']
        ];
        DayOff::insert($data);
    }
}
