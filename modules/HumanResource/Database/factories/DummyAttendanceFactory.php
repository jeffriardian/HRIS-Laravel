<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\HumanResource\Models\DummyAttendance as Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    $date = now()->format('Y-m-d');
    return [
        'employee_code' =>  Modules\HumanResource\Models\Employee::where('salary_type_id', 2)->where('employee_status_id', '!=', 999)->where('is_active', 1)->get()->random()->nik,
        'scan_in' => "$date 06:45:00",
        'scan_out' => "$date 17:10:00",
        'shift_id' => '1',
        'created_at' => now(),
        'updated_at' => now()
    ];
});
