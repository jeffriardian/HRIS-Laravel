<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\HumanResource\Models\Employee as Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    $gender = $faker->randomElement([
        Model::GENDER_MAN,
        Model::GENDER_WOMAN,
    ]);
    $genderName = ($gender == 1 ? 'male' : 'female');
    $birthAt = $faker->dateTimeThisCentury->format('Y-m-d');
    return [
        'department_id' => Modules\HumanResource\Models\Department::all()->random()->id,
        'position_id' => Modules\HumanResource\Models\Position::all()->random()->id,
        'religion_id' => Modules\HumanResource\Models\Religion::all()->random()->id,
        'blood_type_id' => Modules\HumanResource\Models\BloodType::all()->random()->id,
        'marital_status_id' => Modules\HumanResource\Models\MaritalStatus::all()->random()->id,
        'employee_status_id' => Modules\HumanResource\Models\Contract::all()->random()->id,
        'company_id' => Modules\HumanResource\Models\Company::all()->random()->id,
        'office_hour_id' => 1,
        'payroll_type_id' => Modules\HumanResource\Models\PayrollType::all()->random()->id,
        'salary_type_id' => Modules\HumanResource\Models\SalaryType::all()->random()->id,
        'day_off_id' => Modules\HumanResource\Models\DayOff::all()->random()->id,
        'ptkp_code' => Modules\HumanResource\Models\Ptkp::all()->random()->code,
        'nik' => $faker->unique()->numerify('########'),
        'ktp' => $faker->nik($genderName, new DateTime($birthAt)),
        'kk' => $faker->unique()->numerify('################'),
        'npwp' => $faker->unique()->numerify('###############'),
        'sim' => $faker->unique()->numerify('############'),
        'pin' => $faker->unique()->numerify('#####'),
        'name' => $faker->firstName($genderName) . ' ' . $faker->lastName($genderName),
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'gender' => $gender,
        'birth_place' => $faker->city,
        'birth_at' => $birthAt,
        'join_at' => $faker->dateTimeThisCentury->format('Y-m-d'),
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ];
});
