<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\City as Model;
// use Illuminate\Database\Seeder;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR .  'cities.sql'));
    }
}
