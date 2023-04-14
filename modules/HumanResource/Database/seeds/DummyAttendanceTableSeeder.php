<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\HumanResource\Models\DummyAttendance;

class DummyAttendanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::unprepared(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'dummy_attendances.sql'));
        factory(DummyAttendance::class, 10)->create();
    }
}
