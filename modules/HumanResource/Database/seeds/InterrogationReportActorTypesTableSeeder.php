<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\HumanResource\Models\InterrogationReportActorType;

class InterrogationReportActorTypesTableSeeder extends Seeder
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
            ['id' => 1, 'name' => 'Witness', 'created_by' => 1, 'updated_by' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Suspect', 'created_by' => 1, 'updated_by' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];
        InterrogationReportActorType::insert($data);

        // $this->call("OthersTableSeeder");
    }
}
