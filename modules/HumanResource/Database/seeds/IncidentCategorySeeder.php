<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\IncidentCategory;

class IncidentCategorySeeder extends Seeder
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
            ['id' => 1, 'name' => 'Criminal', 'created_by' => 1, 'updated_by' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Damage Property', 'created_by' => 1, 'updated_by' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Work Accident', 'created_by' => 1, 'updated_by' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'External Problem', 'created_by' => 1, 'updated_by' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'Internal Problem', 'created_by' => 1, 'updated_by' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];

        IncidentCategory::insert($data);
    }
}
